@extends('layouts.app')

@section('sidebar-left')
    <topic-sidebar
        :topic="{{ $topic }}"
        tab="{{ $tab }}">
    </topic-sidebar>
@endsection

@section('content')
    <topic-item :topic="{{ $topic }}"></topic-item>

    @if ($tab == 'questions')
        <questions-list start-point="{{ '/questions/get?by_topic='.$topic->id }}"></questions-list>
    @endif

    @if ($tab == 'followers')
        @if ($topic->is_space)
            <div class="card mb-3 border-bottom-0">
                <span class="badge badge-danger float-right p-2 badge-position">
                    <small><i class="fas fa-check"></i></small> {{ __('Owner') }}
                </span>
                <user-item
                    :user="{{ $topic->owner }}"
                    :is-extended-template="true">
                </user-item>
            </div>
        @endif

        <users-list start-point="{{ '/users/get?by_topic='.$topic->id.'&exclude='.$topic->user_id }}"></users-list>
    @endif
@endsection

@section('sidebar-right')
    <the-sidebar-second
        site_name="{{ config('app.name', 'QALAR') }}"
        :topic="{{ $topic }}">
    </the-sidebar-second>
@endsection
