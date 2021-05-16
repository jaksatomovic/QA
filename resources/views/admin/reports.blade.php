@extends('layouts.app')

@section('sidebar-left')
    <the-admin-sidebar></the-admin-sidebar>
@endsection

@section('content')
    <the-admin-reports
        start-point="{{ '/admin/reports/get'.$filter }}">
    </the-admin-reports>
@endsection
