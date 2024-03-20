
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
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h3>{{$res->CourseQuizz->Course->title}} - {{$res->result}} - {{$res->success_rate}} %</h3>

                <div class="exam__card__archive">
                    <h5> {{$res->CourseQuizz->title}}</h5>
                    <ul class="exam__archive">
                        @foreach($res->CourseQuizzAnswers as $value)
                            <li>
                                <p class="question__data"> {{$value->CourseQuizzQuestions->question}}</p>
                                <p class="answer__data"> 
                                @if($value->CourseQuizzQuestions->type === 'choice' )
                                {{$value->CourseQuizzQuestionAnswers->answer}}
                                @endif
                                @if($value->CourseQuizzQuestions->type === 'articl' )
                                {{$value->articl}}
                                @endif
                                </p>
                            </li>
                        @endforeach 
                    </ul>
                </div>
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