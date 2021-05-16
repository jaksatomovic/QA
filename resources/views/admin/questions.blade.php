@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-questions
        start-point="{{ '/admin/questions/get'.$filter }}">
    </the-admin-questions>
@endsection
