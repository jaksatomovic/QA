@extends('layouts.app')

@section('content')
    <topics-list
        start-point="{{ '/topics/get?topics=1' }}"
        type="topics">
    </topics-list>
@endsection
