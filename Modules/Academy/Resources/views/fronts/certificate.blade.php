
@extends('layouts.front')
@section('style')
<title>  الأكاديمية</title>
<style type="text/css">



</style> 
@endsection
@section('content')

    <!-- page tabs -->

    <div class="certificates">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h3>   لقد اتممت كورس {{$cour->title}}</h3>

                    <h3>     الاسم : {{Auth::guard('customer')->user()->name}}</h3>

                    <h3>     البريد : {{Auth::guard('customer')->user()->email}}</h3>
                  
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')



@endsection