
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/companies_details.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/vendors/jquery.fancybox.min.css')}}">
<title>  {{$online->title}} كورس اوفلاين</title>

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

@if(Auth::guard('customer')->user())

@if(!empty($com))
<div class="lecture-page online-course-video">
    <div class="container">
    @include('../parts.alert')
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <h1 class="course__title">كورس  {{$online->title}}</h1>

                <div class="img-container">
                    <img alt="" class="img-fluid"
                            src="{{asset('uploads/courses/avatar/'.$online->image)}}">
                            <div class="overlay">
                                <a class="noe" data-fancybox href="#">
                                    <i class="fas fa-play"></i>
                                </a>
                            </div>
                </div>
             
           

                <div class="content">
                    <div class="accordion" id="accordionExample">
                        @foreach($online->CourseOffline->first()->CourseOfflineFolders as $value)
                            <div class="card">
                                <div class="card-header" id="week{{$value->id}}">
                                    <h5 aria-controls="collapseweek{{$value->id}}" aria-expanded="true" class="mb-0"
                                        data-target="#collapseweek{{$value->id}}"
                                        data-toggle="collapse">
                                        <span> {{$value->name}}</span>
                                        <span>{{count($value->CourseOfflinevideos)}} محاضرات </span>
                                    </h5>
                                </div>

                                <div aria-labelledby="headingweek{{$value->id}}" class="collapse show" data-parent="#accordionExample"
                                    id="collapseweek{{$value->id}}">
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            @foreach($value->CourseOfflinevideos as $val)
                                               
                                            <li>
                                                <a  class="Disabled" href="#" data-fold = "{{$value->id}}" data-id = "{{$val->id}}" data-link = "{{asset('uploads/videos/'.$val->video)}}">
                                                    <span>{{$val->title}}</span>
                                                    <span>{{$val->video}}</span>
                                                    @foreach($uses as $user)
                                                        @if($user->video_id == $val->id)
                                                        <i class="fas fa-check"></i>
                                                        @endif
                                                    @endforeach 
                                                    
                                                </a>

                                  
                             
                    
                                            
                                            </li>
                                            @endforeach
                                            @if(count($value->CourseOfflinevideos) == count($value->usvids()))
                                                @foreach($value->CourseQuizzs as $que)
                                                    
                                                        <a style="border-radius: 12px;
                                                        padding: .3rem .6rem;
                                                        text-decoration: none;
                                                        display: block;
                                                        width: 100%;
                                                        color: #1f6b00;
                                                        background-color: #FFAA00;
                                                        font-weight: 600;
                                                        transition: all .3s ease-in-out;
                                                        font-size: 1.2rem;" href="{{ route('front_exam_courses',$que->id) }}">
                                                        {{$que->title}}</a>
                                                
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach 
                    </div>
                    <p class="q-and-a">سؤال وإجابة</p>

                    <div class="comments">
                        @foreach($coms as $value)
                            <div class="media">
                                <div class="media-body">
                                
                                        <h5 class="mt-0"> {{$value->Customer->name}}</h5>
                                        <p> {{$value->comment}}</p>
                                        @foreach($value->REPComments as $val)
                                        <div class="media mt-3">
                                            <div class="media-body">
                                                <h5 class="mt-0"> {{$val->Customer->name}}</h5>
                                                <p> {{$val->comment}}</p>
                                            </div>
                                        </div>
                                        @endforeach 
                                        <form action="{{route('going_courses_add_replay')}}" method="post" enctype="multipart/form-data">
                                        {{csrf_field()}}
                                        <input type="hidden" name="com_id" value="{{$value->id}}">
                                            <div class="form-group">
                                                <textarea class="form-control"  name="com" placeholder="أكتب ردك.." rows="3"></textarea>
                                            </div>
                                            <button type="submit">إضافة</button>
                                        </form>
                                </div>
                            </div>
                        @endforeach 
                   
                    </div>
                    <hr>
                    <form action="{{route('going_courses_add_comment')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                        <input type="hidden" name="courses_id" value="{{$online->id}}">
                        <div class="form-group">
                            <textarea class="form-control" name="com" placeholder="أكتب تعليقك.." rows="5"></textarea>
                        </div>
                        <button type="submit">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endif
@if(empty($com))
   
<div class="course-info">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="content">
                    <h1 class="main__title">
                        <a> {{$online->title}}</a>
                    </h1>

                    <div class="rate">
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>

                    <div class="price">
                    

                        <div class="after-sale">
                            <h5>{{$online->price_offline}}</h5><span>LE</span>
                        </div>
                    </div>
                    <form class="form__box row justify-content-center" action="{{route('going_courses_online')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="courses_id" value="{{$online->id}}">
                        <button class="buy-now" type="submit">أشترى الأن</button>
                    </form>
                 
                </div>
            </div>
            <div class="col-md-6">
                <div class="img-container">
                    <a href="online-course.html"><img alt="" class="img-fluid"
                                                        src="{{asset('uploads/courses/avatar/'.$online->image)}}"></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- course info -->

<!-- course details -->

<div class="course-details">
    <div class="container">
        <div class="content">
            <h3>تفاصيل الكورس</h3>
            <div class="details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">المحاضر:</h4>
                            <p class="data__body">{{$online->CourseOffline->first()->prof}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">عدد المحاضرات:</h4>
                            <p class="data__body">{{count($online->CourseOffline->first()->CourseOfflinevideos)}} محاضرة</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">عدد الساعات:</h4>
                            <p class="data__body">{{$online->CourseOffline->first()->hourse_count}} ساعة</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- course details -->

<!-- course description -->

<div class="course-description">
    <div class="container">
        <div class="description">
            <h3>وصف الكورس</h3>
            <p>{{$online->desc}}</p>
        </div>
    </div>
</div>

<!-- course description -->

<!-- course content -->

<div class="course-content">
    <div class="container">
        <div class="content">
            <h3>محتوي الكورس</h3>

            <div class="accordion" id="accordionExample">
                @foreach($online->CourseOffline->first()->CourseOfflineFolders as $value)
                    <div class="card">
                        <div class="card-header" id="week{{$value->id}}">
                            <h5 aria-controls="collapseweek{{$value->id}}" aria-expanded="true" class="mb-0"
                                data-target="#collapseweek{{$value->id}}"
                                data-toggle="collapse">
                                <span> {{$value->name}}</span>
                                <span>{{count($value->CourseOfflinevideos)}} محاضرات </span>
                            </h5>
                        </div>

                        <div aria-labelledby="headingweek{{$value->id}}" class="collapse show" data-parent="#accordionExample"
                            id="collapseweek{{$value->id}}">
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    @foreach($value->CourseOfflinevideos as $val)
                                        <li>
                                            <span>{{$val->title}}</span>
                                            <span>{{$val->video}}</span>
                                        </li>
                                    @endforeach 
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach 
            </div>
        </div>
    </div>
</div>
@endif

@else

<div class="course-info">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="content">
                    <h1 class="main__title">
                        <a> {{$online->title}}</a>
                    </h1>

                    <div class="rate">
                        <i class="fas fa-star-half-alt"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>

                    <div class="price">
                    

                        <div class="after-sale">
                            <h5>{{$online->price_offline}}</h5><span>LE</span>
                        </div>
                    </div>

                    <a class="buy-now" href="{{ route('customer_login') }}">إشتري الأن</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="img-container">
                    <a href="online-course.html"><img alt="" class="img-fluid"
                                                        src="{{asset('uploads/courses/avatar/'.$online->image)}}"></a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- course info -->

<!-- course details -->

<div class="course-details">
    <div class="container">
        <div class="content">
            <h3>تفاصيل الكورس</h3>
            <div class="details">
                <div class="row">
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">المحاضر:</h4>
                            <p class="data__body">{{$online->CourseOffline->first()->prof}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">عدد المحاضرات:</h4>
                            <p class="data__body">{{count($online->CourseOffline->first()->CourseOfflinevideos)}} محاضرة</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="data">
                            <h4 class="data__title">عدد الساعات:</h4>
                            <p class="data__body">{{$online->CourseOffline->first()->hourse_count}} ساعة</p>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<!-- course details -->

<!-- course description -->

<div class="course-description">
    <div class="container">
        <div class="description">
            <h3>وصف الكورس</h3>
            <p>{{$online->desc}}</p>
        </div>
    </div>
</div>

<!-- course description -->
{{csrf_field()}}
<!-- course content -->

<div class="course-content">
    <div class="container">
        <div class="content">
            <h3>محتوي الكورس</h3>

            <div class="accordion" id="accordionExample">
                @foreach($online->CourseOffline->first()->CourseOfflineFolders as $value)
                    <div class="card">
                        <div class="card-header" id="week{{$value->id}}">
                            <h5 aria-controls="collapseweek{{$value->id}}" aria-expanded="true" class="mb-0"
                                data-target="#collapseweek{{$value->id}}"
                                data-toggle="collapse">
                                <span> {{$value->name}}</span>
                                <span>{{count($value->CourseOfflinevideos)}} محاضرات </span>
                            </h5>
                        </div>

                        <div aria-labelledby="headingweek{{$value->id}}" class="collapse show" data-parent="#accordionExample"
                            id="collapseweek{{$value->id}}">
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    @foreach($value->CourseOfflinevideos as $val)
                                    
                                        <li>
                                        
                                            <span>{{$val->title}}</span>
                                            <span>{{$val->video}}</span>
                                        </li>
                                    @endforeach 
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach 
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.fancybox.min.js')}}"></script>
<script>

$(document).on('click','.Disabled', function(){
    var link      = $(this).data('link')
    var id      = $(this).data('id')
    var folder_id      = $(this).data('fold')
    var data = {
		courses_id : '{{$online->id}}',
        video_id : id,
        folder_id : folder_id,

		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('academy-courses-watch-online') }}",
	method  : 'post',
	data    : data,
	success : function(s,r){
            console.log(s.rate);
	}});


$('.noe').attr('href',link);

})
 </script>
@endsection