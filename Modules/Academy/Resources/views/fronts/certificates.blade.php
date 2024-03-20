
@extends('layouts.front')
@section('style')
<title>  الأكاديمية</title>
<style type="text/css">



</style> 
@endsection
@section('content')

<!-- page tabs -->
<div class="page__tabs container">
    <ul class="tabs__list">
        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_academy_courses') }}">كورسات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_my_courses') }}">كورساتك</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_exams_courses') }}">الإمتحانات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link active" href="{{ route('front_my_certificates') }}">الشهادات</a>
        </li>
    </ul>
</div>
    <!-- page tabs -->

    <div class="certificates">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h3> الشهادات للكورس المسجل</h3>
                    @foreach($offlines as $value)
                    @if(count($value->Course->CourseOffline->first()->CourseOfflinevideos) == count($value->Course->uses()) && count($value->Course->CourseQuizz) == count($value->Course->resl()))
                    <div class="exam-card">
                        <h5>{{$value->Course->title}}</h5>

                        <ul class="list-unstyled">
                            <li><a href="{{ route('front_my_certificate',$value->Course->id) }}">أحصل علي الشهادة</a></li>
                        </ul>
                    </div>
                    @endif
                    @endforeach 
                </div>
            </div>
        </div>
    </div>

    <div class="certificates">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h3>الشهادات للكورس المباشر</h3>

                    @foreach($lives as $value)
                    @if($value->Course->status_l == 1)
                    <div class="exam-card">
                        <h5>{{$value->Course->title}}</h5>

                        <ul class="list-unstyled">
                            <li><a href="{{ route('front_my_certificate',$value->Course->id) }}">أحصل علي الشهادة</a></li>
                        </ul>
                    </div>
                    @endif
                    @endforeach 
                </div>
            </div>
        </div>
    </div>

    <div class="certificates">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <h3>الشهادات الاوفلاين</h3>

                    @foreach($meetings as $value)
                    @if($value->Course->status_o == 1)
                    <div class="exam-card">
                        <h5>{{$value->Course->title}}</h5>

                        <ul class="list-unstyled">
                            <li><a href="{{ route('front_my_certificate',$value->Course->id) }}">أحصل علي الشهادة</a></li>
                        </ul>
                    </div>
                    @endif
                   
                    @endforeach 
                </div>
            </div>
        </div>
    </div>
    <!-- End Partners -->  <!-- courses slider -->



@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>
<script>
    $(document).ready(function () {
        const options = {
            type: 'loop',
            perPage: 4,
            perMove: 1,
            rewind: true,
            direction: 'rtl',
            width: '100vw',
            pagination: false,
            autoplay: true,
            interval: 5000,
            padding: {
                right: '1rem',
                left: '1rem',
            },
            breakpoints: {
                1200: {
                    perPage: 4,
                    perMove: 1,
                },
                1100: {
                    perPage: 4,
                    perMove: 1,
                },
                950: {
                    perPage: 3,
                    perMove: 1,
                },
                800: {
                    perPage: 2,
                    perMove: 1,
                },
                600: {
                    perPage: 1,
                    perMove: 1,
                },
                450: {
                    perPage: 1,
                    perMove: 1,
                },
                300: {
                    perPage: 1,
                    perMove: 1,
                },
            },
        };
        new Splide('.courses__slider', options).mount();
        new Splide('.courses__slider1', options).mount();
        new Splide('.courses__slider2', options).mount();
    });
</script>


@endsection