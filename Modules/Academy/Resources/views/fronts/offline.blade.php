
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<title>  {{$offline->title}} كورس اوفلاين</title>

<style type="text/css">

.offline-course form .buy__course {
    display: block;
    text-decoration: none;
    color: #1f6b00;
    background-color: #FFAA00;
    font-size: 1.2rem;
    font-weight: bold;
    text-align: center;
    margin: 2rem 0;
    padding: .5rem 0;
    border-radius: 10px;
    transition: all 0.3s ease-in-out;
    width: 100%;
    border: none;
}
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

<!-- lecture page -->
<div class="offline-course">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 class="course__title">كورس  {{$offline->title}}</h1>

                <div class="img-container">
                    <img alt="" class="img-fluid"
                            src="{{asset('uploads/courses/avatar/'.$offline->image)}}">
                </div>
                @if(Auth::guard('customer')->user())




                @if(!empty($com))
                <p class="note">لقد تم حجز مكانك في الكورس</p>

                <div class="qr__code__container">
                        <p>{{Auth::guard('customer')->user()->id}}</p>
                        <p>{{Auth::guard('customer')->user()->name}}</p>
                        <p>{{Auth::guard('customer')->user()->email}}</p>
                        <p>{{Auth::guard('customer')->user()->phone}}</p>

                    </div>

                @endif
                @if(empty($com))
                    <form class="form__box row justify-content-center" action="{{route('going_courses_offline')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="courses_id" value="{{$offline->id}}">
                        <button class="buy__course" type="submit">أشترى الأن</button>
                    </form>
                @endif



                @else
                <a class="buy__course" href="{{ route('customer_login') }}">أشترى الأن</a>

                @endif
                <div class="content">
                <p class="time">{{$offline->CourseMeeting->first()->location}}</p>
                @foreach($offline->CourseMeeting as $value)
                    <div class="day-details">
                        <div class="day">
                        <span> {{$value->title}}</span>
                        <span>  {{$value->hourse_count}} ساعة </span>
                        </div>

                        <p class="time">{{$value->date}} - {{$value->time}}</p>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!-- lecture page -->
@endsection

@section('script')
<script>

 </script>
@endsection