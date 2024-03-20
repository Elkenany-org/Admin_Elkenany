
@extends('layouts.front')
@section('style')

<link rel="stylesheet" href="{{asset('Front_End/css/no-data.css')}}">
<title> not found</title>
@endsection
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card__main">
                <h1 class="no__data-title">لا يوجد بيانات</h1>
                <div class="no__data-img">
                    <img src="{{asset('Front_End/images/404.png')}}" alt="404 page">
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')

<script>
</script>
@endsection