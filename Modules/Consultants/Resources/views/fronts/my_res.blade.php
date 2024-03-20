<!DOCTYPE html>
<html lang="ar">

<head>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NYH035MQGZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-NYH035MQGZ');
      console.log(' Google Analytics Is Fired')
    </script>


    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="This is Description Area" name="description">
    <!-- Icon -->
    <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
    <!-- Title -->
    <title> {{$sections->name}} حجزاتي</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Front_End/vendors/css/all.min.css')}}">
    <!-- slick -->
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick-theme.css')}}" />
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/styles.css')}}">
    <!-- My CSS -->
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

    <header class="banner__header">      
    <div class="container-fluid one-full-slider-banner">
        @foreach($adss as $ad)
            <a href="{{$ad->link}}" target="_blank"><img alt="banner" class="banner" src="{{asset('uploads/full_images/'.$ad->image)}}"></a>
        @endforeach 
    </div>
</header>

<article class="partners slider my-4 mt-5">
        <div class="container-fluid logos__holder ">

            <section class="partners-slider" dir='rtl'>
            @foreach($logos as $logo)
                <div class="item">
                <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{asset('uploads/full_images/'.$logo->image)}}"></a>
                <div class="wall"></div>
                </div>
            @endforeach 
             
            </section>
        </div>
    </article>
    <!-- End Navigation Bar -->
  

    <!-- departments nav -->
   <!-- Start Global Sections -->
   <article class="container global__container">
            <!-- Start Content -->

            <div class="page__tabs container">
                <ul class="tabs__list">
                    <li class="list__item list__item-2">
                        <a class="list__link" href="{{ route('front_doctors',$sections->id) }}">الاستشاريون</a>
                    </li>
                    <li class="list__item list__item-2">
                        <a class="list__link active" href="{{ route('front_my_res',$sections->id) }}">مواعيدك</a>
                    </li>
                </ul>
        
            </div>
            
            <section class="all-cards-dates">
                <!-- Start One Card -->
                @if(Auth::guard('customer')->user())
                @if(!empty($orders))
                    @foreach($orders as $order)
                        <a class="one-card">
                            <section class="top">
                                    <section class="left-content">
                                        <h2 class="status">تم الحجز</h2>
                                        <h2 class="main-title"> مع د/ {{$order->Doctor->name}}</h2>
                                        <p class="date">
                                        {{$order->DoctorServices->date}}
                                        </p>
                                        <p class="time">
                                            من الساعة  {{Date::parse($order->DoctorServices->time_from)->format('h:i A')}}
                                        </p>
                                        <p class="time">
                                            الي الساعة {{Date::parse($order->DoctorServices->time_to)->format('h:i A')}}
                                        </p>
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address">{{$order->Doctor->adress}}</span>
                                        </section>
                                    </section>
                            </section>
                        </a>
                    @endforeach
                @endif
                @endif
                <!-- End One Card -->
                <!-- Start One Card No Dates -->
                @if(Auth::guard('customer')->user())
                    @if(count($orders) == 0)
                        <a class="one-card no-dates">
                            <section class="top">
                                <section class="left-content">
                                    <h2 class="status">لم يتم</h2>
                                    <h2 class="main-title">حجز موعد حتي الان</h2>
                                </section>
                            </section>
                        </a>
                    @endif
                @endif
                <!-- End One Card No Dates -->
            </section>
       
    </article>
    <!-- End Global Sections -->
    @include('../fronts.footer')


    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
    <!-- JQuery -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
    <!-- Slick -->
    <script src="{{asset('Front_End/vendors/slick/slick.min.js')}}"></script>
    <script src="{{asset('Front_End/js/slick_initialising.js')}}"></script>
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>

    <script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
  
</body>

</html>