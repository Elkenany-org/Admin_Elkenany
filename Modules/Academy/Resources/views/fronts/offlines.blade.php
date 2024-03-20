
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<title>  الكورسات الاوفلاين  </title>

<style type="text/css">

</style>
@endsection
@section('content')
<div class="page__tabs container">
    <ul class="tabs__list">
        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_academy_courses') }}">كورسات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_my_courses') }}">كورساتك</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_exams_courses') }}">الإمتحانات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_my_certificates') }}">الشهادات</a>
        </li>
    </ul>
</div>
<section class="courses-slider">
        <div class="container">
            <h2 class="sub__header">
                <a href="live-courses.html">الكورسات الاوفلاين</a>
            </h2>

            <div class="row">
            @foreach($offlines as $value)
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="course-card">
                        <div class="img-container">
                            <a href="{{ route('front_offline_courses',$value->id) }}"><img alt=""
                                                                    class="img-fluid"
                                                                    src="{{asset('uploads/courses/avatar/'.$value->image)}}"></a>
                        </div>

                        <h4>
                            <a href="{{ route('front_offline_courses',$value->id) }}"> {{$value->title}}</a>
                        </h4>

                        <div class="rate">
                            <i class="fas fa-star-half-alt"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>

                        <div class="price">
                        

                            <div class="after-sale">
                                <h5>{{$value->price_meeting}}</h5><span>LE</span>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach 

            </div>
        </div>
    </section>
@endsection

@section('script')
<script>

</script>
@endsection