@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-topics
        start-point="{{ '/admin/topics/get'.$filter }}">
    </the-admin-topics>
@endsection
