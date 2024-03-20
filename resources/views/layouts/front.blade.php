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
    <!-- <script type="module" defer src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.6.1/firebase.js"></script>
   
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
 
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script> -->
    <!-- Icon -->
    <link rel="icon" href="{{asset('Front_End/images/favicon.png')}}">
    <!-- Title -->
   
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/bootstrap.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('Front_End/css/vendors/all.min.css')}}">
    <!-- slick -->
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('Front_End/vendors/slick/slick-theme.css')}}" />
    <link href="{{asset('Front_End/css/vendors/splide.min.css')}}" rel="stylesheet">
    <link href="{{asset('Front_End/css/vendors/splide-default.min.css')}}" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/styles.css')}}">


    @yield('style')
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
    <!-- End Navigation Bar -->

    @yield('content')
</div>
    @include('../fronts.footer')

    {{--  Google Analytics  --}}
    
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
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>
    <script src="{{asset('Front_End/js/splide.min.js')}}"></script>
    <!-- End Scripts -->
 
    <script src="{{asset('Front_End/js/rater.min.js')}}"></script>
    <script src="{{asset('Front_End/js/rate_initialising.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>




    @yield('script')

</body>

</html>