@extends('layouts.app')

@section('content')
    <questions-list
        start-point="{{ '/questions/'.$method.$filter }}"
        method="{{ $method }}">
    </questions-list>
@endsection
