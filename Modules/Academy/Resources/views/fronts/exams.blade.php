
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
            <a class="list__link " href="{{ route('front_my_courses') }}">كورساتك</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link active" href="{{ route('front_exams_courses') }}">الإمتحانات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_my_certificates') }}">الشهادات</a>
        </li>
    </ul>
</div>
<!-- page tabs -->
<div class="exams">
    <div class="container">
    @include('../parts.alert')
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h3>الإمتحانات</h3>
                @foreach($courses as $value)
                <div class="exam-card">
                    <h5>{{$value->Course->title}}</h5>
                    <ul class="list-unstyled">
                        @foreach($value->Course->CourseQuizz as $val)
                        @if($val->folder_id == null)
                        <li><a href="{{ route('front_exam_courses',$val->id) }}"> {{$val->title}}</a></li>
                        @endif 
                      

                        @endforeach 
                    </ul>
                </div>

                @endforeach 
                
            </div>
        </div>
    </div>
</div>

<div class="exams">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h3>الإمتحانات السابقة</h3>
                @foreach($courses as $value)
                <div class="exam-card">
                    <h5>{{$value->Course->title}}</h5>
                    <ul class="list-unstyled">
                        @foreach($value->Course->CourseQuizz as $val)
                            @foreach($olds as $vau)
                                @if($val->id == $vau->quizz_id)
                                <li><a href="{{ route('front_exam_archive_courses',$val->id) }}"> {{$val->title}}</a></li>
                                @endif 
                            
                            @endforeach 
                        @endforeach 
                    </ul>
                </div>

                @endforeach 
            </div>
        </div>
    </div>
</div>


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