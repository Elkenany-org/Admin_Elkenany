
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/consultants.css')}}">
@endsection
@section('content')


 <!-- Start Global Content -->
 <article class="global-content">
    <div class="container holder">
        <div class="row">

   
            <!-- Start Left Section -->
            <section class="left col-lg-12 col-md-12 col-sm-12">
                <section class="all-cards">
                    <!-- Start One Card -->
                    @foreach($majors as $major)
                        <a href="{{ route('front_sections',$major->name) }}" class="regular-cards one-card">
                            <section class="logo-box">
                                <img class="card-image" src="{{asset('uploads/majors/avatar/'.$major->image)}}" alt="card image">
                            </section>
                            <section class="content-box">
                                <h2>  استشاريون  {{$major->name}} </h2>
                            </section>
                            <section class="one-full-slider left-box">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                            </section>
                        </a>
                    @endforeach
                    <!-- End One Card -->
                    
                </section>
            </section>
            <!-- End Left Section -->

            </div>
        </div>
    </article>
    <!-- End Global Content -->

@endsection

@section('script')

@endsection