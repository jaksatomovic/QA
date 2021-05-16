@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-answers
        start-point="{{ '/admin/answers/get'.$filter }}">
    </the-admin-answers>
@endsection
