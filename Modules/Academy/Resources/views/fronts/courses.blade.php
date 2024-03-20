
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
            <a class="list__link active" href="{{ route('front_academy_courses') }}">كورسات</a>
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
    <!-- page tabs -->
    <!-- End Partners -->  <!-- courses slider -->
<div class="courses-slider">
    <div class="container">
        <h2 class="sub__header">
            <a href="{{ route('front_all_online') }}">الكورسات المسجلة</a>
        </h2>
        <section class="splide  courses__slider1">
            <div class="splide__track courses">
                <ul class="splide__list">
                    @foreach($offlines as $value)
                        <li class="splide__slide">
                            <div class="course-card">
                                <div class="img-container">
                                    <a href="{{ route('front_online_courses',$value->id) }}"><img alt="" class="img-fluid"
                                                                    src="{{asset('uploads/courses/avatar/'.$value->image)}}"></a>
                                </div>

                                <h4>
                                    <a href="{{ route('front_online_courses',$value->id) }}"> {{$value->title}}</a>
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
                                        <h5>{{$value->price_offline}}</h5><span>LE</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach 
                </ul>
            </div>
        </section>
    </div>
</div>

<div class="courses-slider">
    <div class="container">
        <h2 class="sub__header">
            <a href="{{ route('front_all_live') }}">الكورسات المباشرة</a>
        </h2>
        <section class="splide  courses__slider2">
            <div class="splide__track courses">
                <ul class="splide__list">
                    @foreach($lives as $value)
                        <li class="splide__slide">
                            <div class="course-card">
                                <div class="img-container">
                                    <a href="{{ route('front_live_courses',$value->id) }}"><img alt="" class="img-fluid"
                                                                    src="{{asset('uploads/courses/avatar/'.$value->image)}}"></a>
                                </div>

                                <h4>
                                    <a href="{{ route('front_live_courses',$value->id) }}"> {{$value->title}}</a>
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
                                        <h5>{{$value->price_live}}</h5><span>LE</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach 
                </ul>
            </div>
        </section>
    </div>
</div>

<div class="courses-slider">
    <div class="container">
        <h2 class="sub__header">
            <a href="{{ route('front_all_offline') }}">الكورسات الاوف لاين</a>
        </h2>
        <section class="splide  courses__slider">
            <div class="splide__track courses">
                <ul class="splide__list">
                    @foreach($meetings as $value)
                        <li class="splide__slide">
                            <div class="course-card">
                                <div class="img-container">
                                    <a href="{{ route('front_offline_courses',$value->id) }}"><img alt="" class="img-fluid"
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
                        </li>
                    @endforeach 
                </ul>
            </div>
        </section>
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