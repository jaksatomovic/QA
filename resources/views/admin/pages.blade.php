@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-pages
        start-point="{{ '/admin/pages/get'.$filter }}">
    </the-admin-pages>
@endsection
