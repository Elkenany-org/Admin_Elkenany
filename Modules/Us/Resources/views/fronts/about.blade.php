
@extends('layouts.front')
@section('style')
<title>    من نحن</title>
@endsection
@section('content')
<div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card__main">
                    <h1 class="main__title">من نحن</h1>
                    <div class="description">
                        <p class="content">
                        {!!$setting->about_ar!!}
                        </p>
                  
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
@section('script')

@endsection