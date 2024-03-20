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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="This is Description Area">
    <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase.js"></script>
   
    <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase-app.js"></script>
    <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase-analytics.js"></script>
    <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase-messaging.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase-auth.js"></script>

<script type="module" defer>

// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

// Your web app's Firebase configuration
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
    apiKey: "AIzaSyD2Z4iK1UuZFoCOJN_9i87JgQTq12_GymY",
    authDomain: "elkenany-fbdc6.firebaseapp.com",
    databaseURL: "https://elkenany-fbdc6.firebaseio.com",
    projectId: "elkenany-fbdc6",
    storageBucket: "elkenany-fbdc6.appspot.com",
    messagingSenderId: "711464214583",
    appId: "1:711464214583:web:e417d914fc8dea121b5fb2",
    measurementId: "G-NFRYJC0Q5F"
  };

  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

    
  </script>
 
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Icon -->
    <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
    <!-- Title -->
    <title>   {{$ads->title}}</title>
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

<div class="container add__details__main">
    <div class="row" dir="ltr">
        <div class="col-12 col-md-3">
            <div class="small__bar__container">
                <div class="price__container">
                    <p class="price__content">{{$ads->salary}} ج.م</p>
                </div>

                @if($ads->user_id != null)
                    @if(Auth::guard('customer')->user())
                        @if($ads->user_id != Auth::guard('customer')->user()->id)
                        <form action="{{route('front_start_chat',$ads->id)}}" method="post">
                            {{csrf_field()}}
                        
                            <button class="single__action" type="submit" style="width: 100%;">  <i class="far fa-envelope icon"></i>  ارسال رسالة </button>
                    
                        </form>
                        @endif
                    @else
                    <a class="single__action" href="{{ route('customer_login') }}">
                        <i class="far fa-envelope icon"></i>
                        ارسال رسالة
                    </a>
                    @endif
                @endif
                <a class="single__action" href="tel:+{{$ads->phone}}">
                    <i class="fas fa-phone icon"></i>
                    {{$ads->phone}}
                </a>
                <div class="address__map">
                    <div class="overlay">
                        <div class="map__icon">
                            <i class="fas fa-map-marker-alt icon"></i>
                        </div>
                        <div class="address__data">
                            <p class="address__content">{{$ads->address}}</p>
                            <a class="address__link" href="https://maps.google.com/?q={{$ads->address}}"
                               target="_blank">أظهر على الخريطة</a>
                        </div>
                    </div>
                </div>
                <div class="user__info">
                    <div class="user__icon">
                        <i class="fas fa-user-alt icon"></i>
                    </div>
                    <div class="user__data">
                        <p class="user__name">
                        @if($ads->user_id == null)
                        {{$ads->User->name}}
                        @endif
                        @if($ads->admin_id == null)
                        {{$ads->Customer->name}}
                        @endif
                       
                        </p>
                        <p class="user__date">على الموقع 
                        
                        @if($ads->user_id == null)
                        {{Date::parse($ads->User->created_at)->diffForHumans()}}
                   
                        @endif
                        @if($ads->admin_id == null)
                        {{Date::parse($ads->Customer->created_at)->diffForHumans()}}
                        @endif</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-9">
            <h1 class="main__title">  {{$ads->title}}</h1>
            <p>تم إضافة الإعلان في {{$ads->created_at}} , رقم الإعلان: {{$ads->id}}</p>
          
            <div class="images__slider__top__single slider__for">
                @foreach($ads->StoreAdsimages as $value)
                    <div class="single__slide">
                        <img class="" src="{{asset('uploads/stores/alboum/'.$value->image)}}" alt="partner logo">
                    </div>
                @endforeach  
            </div>
            <div class="images__slider" id="slider__nav">
                @foreach($ads->StoreAdsimages as $value)
                    <div class="single__slide">
                        <div class="logo__holder">
                            <img class="" src="{{asset('uploads/stores/alboum/'.$value->image)}}" alt="partner logo">
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="add__details">
          
                <p class="add__description">
                {{$ads->desc}}
                </p>
            </div>
        </div>
    </div>
</div>

</div>
    @include('../fronts.footer')


    <!-- Start Scripts -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
    <script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
    <!-- JQuery -->
    <script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
    <!-- Slick -->
    <script src="{{asset('Front_End/vendors/slick/slick.min.js')}}"></script>
    <script src="{{asset('Front_End/js/slick_initialising.js')}}"></script>

    <script>
    $('.slider__for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: false,
        focusOnSelect: false,
        autoplaySpeed: 3000,
        speed: 1000,
        pauseOnHover: false,
        pauseOnFocus: false,
        dots: false,
        infinite: false,
        centerMode: false,

    });
    $('#slider__nav').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider__for',
        autoplay: false,
        focusOnSelect: true,
        autoplaySpeed: 3000,
        speed: 1000,
        pauseOnHover: true,
        pauseOnFocus: true,
        dots: true,
        infinite: true,
        centerMode: true,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 770,
                settings: {
                    slidesToShow: 1,
                    centerMode: false
                }
            }
        ]
    });
</script>
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>





</body>

</html>

