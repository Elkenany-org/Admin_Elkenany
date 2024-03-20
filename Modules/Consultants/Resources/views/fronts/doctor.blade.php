<!DOCTYPE html>
<html lang="ar">

<head>
    <!-- Required meta tags -->
       <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NYH035MQGZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-NYH035MQGZ');
      console.log(' Google Analytics Is Fired')
    </script>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="This is Description Area" name="description">
    <!-- Icon -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
    <!-- Title -->
    <title> {{$doctor->name}}</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/all.min.css')}}">
    <!-- slick -->
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick-theme.css')}}" />
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/styles.css')}}">
    </head>
<body>
<div class="main__content__body">
  <!--Start Loading Screen-->
  <article class="loading-screen">
        <div class="chicken-loader">
            <span></span>
        </div>
    </article>
    <!--End Loading Screen-->

    <!-- Start button to top -->
    <button class="btn_top" id="myBtn">
        <span class="arrow"></span>
    </button>
    <!-- End button to top -->

    <!-- Start Navigation Bar -->
    @include('../fronts.sidebar')
    <!-- Start Global Sections -->
     <article class="global__container">
        <div class="container">
                <!-- Start Content -->
                <section class="consultants__content">
                    <!-- Start All Cards -->
                    <section class="all-cards">
                        <!-- Start One Card -->
                        <section class="one-card">
                            <section class="top">
                                <section class="right-ppic">
                                    <img src="{{asset('uploads/doctors/avatar/'.$doctor->avatar)}}" alt="ppic">
                         
                                  
                                </section>
                                  
                            
                
                            
                                
                                <section class="main-content">
                                    <h2 class="main-title">د/ {{$doctor->name}}</h2>
                                   
                                    <p class="fields__container">
                                        <span class="body__title">العنوان : </span>
                                        <span class="body__content"><a href="https://maps.google.com/?q={{$doctor->adress}}"
                                                                    target="_blank">{{$doctor->adress}}</a></span>
                                    </p>
                                    <p class="fields__container">
                                        <span class="body__title">التلفون : </span>
                                        <span class="body__content"><a href="tel:+{{$doctor->phone}}">+{{$doctor->phone}}</a></span>
                                    </p>
                                    <p class="fields__container">
                                        <span class="body__title">الخبرة : </span>
                                        <span class="body__content"> {{$doctor->experiences}}  </span>
                                    </p>

                                    @if(!empty($rating))
                

                                        <div class="main__rating d-flex align-items-center justify-content-center">
                                            <div class="global__rating">
                                                <i id="rating__star" class="upd rating__star far fa-star"></i>
                                                <i id="rating__star" class="upd rating__star far fa-star"></i>
                                                <i id="rating__star" class="upd rating__star far fa-star"></i>
                                                <i id="rating__star" class="upd rating__star far fa-star"></i>
                                                <i id="rating__star" class="upd rating__star far fa-star"></i>
                                            </div>
                                            <div class="mx-2">
                                                <span class="rating__result">{{$rating->rate}}/5</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if(empty($rating))
                                
                                        <div class="main__rating d-flex align-items-center justify-content-center">
                                            <div class="global__rating"> 
                                                <i class="rating rating__star far fa-star"></i>
                                                <i class="rating rating__star far fa-star"></i>
                                                <i class="rating rating__star far fa-star"></i>
                                                <i class="rating rating__star far fa-star"></i>
                                                <i class="rating rating__star far fa-star"></i>
                                            </div>
                                            <div class="mx-2">
                                                <span class="rating__result">0/5</span>
                                            </div>
                                        </div>
                                    @endif
                                </section>
                            </section>
                        </section>
                        <!-- End One Card -->
                       <!-- Start Testimonials -->
                        <article class="testimonials">
                            <!-- Start Slider -->
                            <article class="market slider">
                                <div class="holder">
                                    <section class="market-slider">
                                        @foreach($doctor->DoctorLinks as $value)
                                            <div class="item">
                                                <a href="{{$value->link}}" class="logo-holder">
                                                    <img src="{{asset('uploads/doctors/image/'.$value->image)}}" alt="partner logo">
                                                    {{$value->title}}
                                                </a>
                                                <span id="trigger-1-title" class="d-none"> {{$value->title}}</span>
                                        
                                            </div>
                                     
                                        @endforeach
                                    </section>
                                </div>
                            </article>
                            <!-- End Slider -->
                        </article>
                        <!-- End Testimonials -->
                        <!-- Start Details And Reservation -->
                        <section class="details-and-reservation">
                            <section class="top">
                                <section class="main-content">
                                    <p class="note">
                                        عند إتمام الحجز يتم ارسال كافة التفاصيل في الرسايل
                                    </p>
                                    <section class="boxes">
                                        <a class="box" href="{{ route('front_orders_call',$doctor->id) }}">
                                            <section class="reservation-top">
                                                <span class="title">استشارة هاتفية</span>
                                                <span class="price">{{$doctor->call_price}} ج</span>
                                            </section>
                                            <section class="reserve-box">
                                                <span class="reserve-now">احجز الان</span>
                                            </section>
                                        </a>
                                        <a class="box" href="{{ route('front_orders_online',$doctor->id) }}">
                                            <section class="reservation-top">
                                                <span class="title">كشف اونلاين</span>
                                                <span class="price">{{$doctor->online_price}} ج</span>
                                            </section>
                                            <section class="reserve-box">
                                                <span class="reserve-now">احجز الان</span>
                                            </section>
                                        </a>
                                        <a class="box" href="{{ route('front_orders_meeting',$doctor->id) }}">
                                            <section class="reservation-top">
                                                <span class="title">زيارة الي الاستشاري</span>
                                                <span class="price">{{$doctor->meeting_price}} ج</span>
                                            </section>
                                            <section class="reserve-box">
                                                <span class="reserve-now">احجز الان</span>
                                            </section>
                                        </a>
                                    </section>
                                    <span class="warning">يرجي الالتزام بالمعاد المحدد</span>
                                </section>
                            </section>
                        </section>
                        <!-- End Details And Reservation -->
                    </section>
                    <!-- End All Cards -->
                </section>
                <!-- End Content -->
                {{csrf_field()}}
            </div>
        </article>
        <!-- End Global Sections -->
        @include('../fronts.footer')
        
      
    <!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
    <!-- JQuery -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
    <!-- Slick -->
    <script src="{{asset('Front_End/vendors/slick/slick.min.js')}}"></script>
    <script src="{{asset('Front_End/js/slick_initialising.js')}}"></script>
    <script src="{{asset('Front_End/js/rater.min.js')}}"></script>
    <script src="{{asset('Front_End/js/rate_initialising.js')}}"></script>
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>
    <script type="text/javascript">
  


const ratingResult = document.querySelector(".rating__result");

function ratingResultCallback(result) {
    ratingResult.textContent = `${result}/5`;
    //  and Then Do Any thing with the rating results
@if(empty($rating))
    $(document).on('click','.rating__star', function(){
    var data = {
		doctor_id : '{{$doctor->id}}',
        reat : result,
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
    url     : "{{ url('consultant-rate') }}",
	method  : 'post',
	data    : data,
	success : function(s,r){
        console.log(s.rate);
	}});
});
@endif
@if(!empty($rating))
$(document).on('click','.rating__star', function(){
    var data = {
		doctor_id : '{{$doctor->id}}',
        reat : result,
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
        url     : "{{ url('consultant-update-rating') }}",
	method  : 'post',
	data    : data,
	success : function(s,r){
            console.log(s.rate);
	}});
});
@endif
}

executeRating(ratingStars, ratingResultCallback);
    </script>

    </body>

</html>