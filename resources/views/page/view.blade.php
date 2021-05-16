@extends('layouts.app')

@section('content')
    <div class="mb-3 d-flex justify-content-between">
        <div class="btn bg-white border">
            <span>{{ $page->title }}</span>
        </div>
    </div>

    <div class="bg-white">
        <div class="card">
            <div class="card-body p-3">
                {!! $page->body !!}
            </div>
        </div>
    </div>
@endsection
