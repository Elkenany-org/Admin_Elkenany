
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<title>  {{$live->title}} كورس مباشر</title>

<style type="text/css">

.lecture-page form .note__link {
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
<div class="lecture-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 class="course__title">كورس  {{$live->title}}</h1>

                <div class="img-container">
                    <img alt="" class="img-fluid"
                            src="{{asset('uploads/courses/avatar/'.$live->image)}}">
                </div>
                @if(Auth::guard('customer')->user())




                    @if(!empty($com))
                    <a class="note" href="#">أدخل إلي المحاضرة الآن</a>
                    @endif
                @if(empty($com))
                    <form class="form__box row justify-content-center" action="{{route('going_courses_live')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="courses_id" value="{{$live->id}}">
                        <button class="note__link" type="submit">أشترى الأن</button>
                    </form>
                @endif



                @else
                <a class="note__link" href="{{ route('customer_login') }}">أشترى الأن</a>

                @endif

                <div class="content">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="week1">
                                <h5 aria-controls="collapseweek1" aria-expanded="true" class="mb-0"
                                    data-target="#collapseweek1" data-toggle="collapse">
                                    <span> {{$live->title}}</span>
                                    <span>   {{count($live->CourseLive)}} محاضرات </span>
                                </h5>
                            </div>

                            <div aria-labelledby="headingweek1" class="collapse show"
                                    data-parent="#accordionExample"
                                    id="collapseweek1">
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                    @if(Auth::guard('customer')->user())
                                        @foreach($live->CourseLive as $value)
                                            @if($value->date <= Carbon\Carbon::today())
                                                <li>
                                                    <a class="Disabled" href="#" data-link = "{{$value->link}}">
                                                        <span> {{$value->title}} - {{$value->hourse_count}} ساعة</span>
                                                        <span>  {{$value->date}} - {{$value->time}} </span>
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li>
                                                    <a class="isDisabled" href="#" data-link = "{{$value->link}}">
                                                        <span> {{$value->title}} - {{$value->hourse_count}} ساعة</span>
                                                        <span>  {{$value->date}} - {{$value->time}} </span>
                                                        <i class="fas fa-check"></i>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($live->CourseLive as $value)
                                            <li>
                                                <a class="isDisabled" href="#">
                                                    <span> {{$value->title}} - {{$value->hourse_count}} ساعة</span>
                                                    <span>  {{$value->date}} - {{$value->time}} </span>
                                                    <i class="fas fa-check"></i>
                                                </a>
                                            </li>
                                        @endforeach

                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- lecture page -->
@endsection

@section('script')
<script>
$('.Disabled').on('click',function(){


        var link      = $(this).data('link')



        $('.note').attr('href',link);


       
    })
    </script>
@endsection