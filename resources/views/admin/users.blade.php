@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-users
        start-point="{{ '/admin/users/get'.$filter }}">
    </the-admin-users>
@endsection
