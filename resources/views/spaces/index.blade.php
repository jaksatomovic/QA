@extends('layouts.app')

@section('sidebar-left')
    <the-sidebar-main action="add_space"></the-sidebar-main>
@endsection

@section('content')
    <topics-list
        start-point="{{ '/topics/get?spaces=1' }}"
        type="spaces">
    </topics-list>
@endsection
