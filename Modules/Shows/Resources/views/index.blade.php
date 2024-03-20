@extends('shows::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: {!! config('shows.name') !!}
    </p>
@endsection
