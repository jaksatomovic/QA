@extends('layouts.app')

@if ($user->status != 1)
    @section('sidebar-left')
        <span></span>
    @endsection

    @section('content')
        <div class="card text-center p-5">
            <h1><i class="fas fa-user-lock fa-2x text-app-primary"></i></h1>
            <p class="mt-4">{{ __('Seems that this account is closed') }}</p>
            <div class="mt-2">
                <a href="#" class="btn btn-light border px-3" onClick="javascript:history.go(-1)">{{ __('Go Back') }}</a>
            </div>
        </div>
    @endsection
@else
    @section('sidebar-left')
        <user-sidebar
            :user="{{ $user }}"
            tab="{{ $tab }}">
        </user-sidebar>
    @endsection

    @section('content')
        <div class="card mb-3">
            <user-item
                :user="{{ $user }}"
                tab="{{ $tab }}">
            </user-item>
        </div>

        @if ($tab == 'questions')
            <questions-list
                start-point="{{ '/questions/get?added_by='.$user->id }}">
            </questions-list>
        @endif

        @if ($tab == 'answers')
            <answers-list
                start-point="{{ '/users/id'.$user->id.'/answers' }}">
            </answers-list>
        @endif

        @if ($tab == 'topics')
            <topics-list
                start-point="{{ '/topics/get?topics=1&followed_by='.$user->id }}"
                type="topics">
            </topics-list>
        @endif

        @if ($tab == 'spaces')
            <topics-list
                start-point="{{ '/topics/get?spaces=1&followed_by='.$user->id }}"
                type="spaces">
            </topics-list>
        @endif
    @endsection
@endif
