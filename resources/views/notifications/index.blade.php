@extends('layouts.app')

@section('content')
    <notifications-list start-point="{{ '/notifications/get' }}"></notifications-list>
@endsection
