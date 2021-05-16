@extends('layouts.app')

@section('content')
    <div class="card mb-3">
        <div class="card-body p-0">
            <question-item :question="{{ $question }}"></question-item>
        </div>
    </div>

    <answers-list
        :question="{{ $question }}"
        start-point="/questions/{{ $question->id }}/answers"
        :is-extended-template="true">
    </answers-list>
@endsection

@section('sidebar-right')
    <the-sidebar-second
        site_name="{{ config('app.name', 'QALAR') }}"
        :question="{{ $question }}">
    </the-sidebar-second>
@endsection
