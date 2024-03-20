
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
    <link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
    <title>    تفاصيل   {{$companies->name}} </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="This is Description Area">

<script type="module" src="https://www.gstatic.com/firebasejs/9.0.1/firebase-app.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/8.2.2/firebase.js"></script>

<script type="module" src="https://www.gstatic.com/firebasejs/9.0.1/firebase-messaging.js"></script>

<script type="module" src="https://www.gstatic.com/firebasejs/9.0.1/firebase-analytics.js"></script>
<script type="module">

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
    <script type="module" src="https://www.gstatic.com/firebasejs/9.0.1/firebase-messaging-sw.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
<section class="container global__container">
    <section class="company__details__container">
        @if($companies->paied === 0)
        
        <!-- Start Company Info  -->
        <article class="cards company-info">
            <h2 class="main-title"> {{$companies->name}}</h2>
            <section class="image-holder">
                <img class="company-image" src="{{asset('uploads/company/images/'.$companies->image)}}" alt="card image">
            </section>
            <p>
            {{$companies->short_desc}}
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
            <p class="raters-number" style="display: block;"> {{count($companies->CompanyRates)}} عميل</p>  
        </article>
        <!-- End Company Info  -->

        <!-- Start About Company -->
        <article class="cards about-company">
            <h2 class="main-title">عن الشركة</h2>
            <p>
            {{$companies->about}}
            </p>
        </article>
        <!-- End About Company -->
        @endif
        @if($companies->paied === 1 || Auth::guard('customer')->user()->memb == '1')
        <!-- Start Company Info  -->
        <article class="cards company-info">
            <h2 class="main-title"> {{$companies->name}}</h2>
            <section class="image-holder">
                <img class="company-image" src="{{$companies->image_url}}" alt="{{$companies->name}}" style="width: 200px;height: 200px">
            </section>
            <p>
            {{$companies->short_desc}}
            </p>
           
           
            @if(!empty($rating))

                <div class="main__rating d-flex align-items-center justify-content-center">
                    <div class="global__rating">
                        @for($i = 0;$i<round($rating->rate);$i++)
                            <i id="rating__star" class="rating__star fas fa-star"></i>
                        @endfor
                        @for($i = 0;$i<5-round($rating->rate);$i++)
                            <i id="rating__star" class="upd rating__star far fa-star"></i>
                        @endfor
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

            <p class="raters-number" style="display: block;">
                <span id="msg-rate" style="display: none"><small>تم التقييم بنجاح</small><br></span>
                <span id="msg-auth" style="display: none"><small> برجاء<a href="/customer/login"> تسجيل الدخول</a> </small><br></span>

                <span class="customers_rate"> {{count($companies->CompanyRates)}}</span> عميل</p>
               
      
        </article>
        <!-- End Company Info  -->

        <!-- Start About Company -->
        <article class="cards about-company">
            <h2 class="main-title">عن الشركة</h2>
            <p>
            {{$companies->about}}
            </p>
        </article>
        <!-- End About Company -->
        <!-- Start Company Products -->
        @if(count($companies->Companyproduct) !== 0)
        <article class="cards company-products">
            <h2 class="main-title">المنتجات</h2>
            <!-- Start Slider -->
            <article class="products slider">
                <div class="container-fluid logos__holder">
                    <section class="products-slider" dir='rtl'>
                        @foreach($companies->Companyproduct as $key => $value)
                            <div class="item">
                                <a class="logo-holder">
                                    <img src="{{asset('uploads/company/product/'.$value->image)}}" alt="partner logo">
                                  
                                </a>
                                {{$value->name}}
                            </div>
                        @endforeach 
                    </section>
                </div>
            </article>
            <!-- End Slider -->
        </article>
        @endif
        <!-- End Company Products -->

        <!-- Start Transport Cost -->
        @if(count($transports) !== 0)
        <article class="cards transport-cost">
            <h2 class="main-title">تكلفة النقل</h2>
            <div class="container-fluid transport-holder">
                <div class="row justify-content-center align-items-center">
                    <div class="col-12 col-md-6">
                        <div class="content">
                        <p class="content__title">تكلفة نقل الكتكوت</p>
                            <article class="table__transport container">
                                <section class="head">
                                    <h3 class="title">تكلفة نقل الكتكوت</h3>
                                    <!-- Start Dropdown Item -->
                                    <section class="title title__categories">
                                        <select class="city1 select__categories">
                                        @foreach($cities as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach 
                                        </select>
                                    </section>
                                    <!-- End Dropdown Item -->
                                </section>
                                <!-- Start One Row  -->
                                <section class="rows chek">
                                    @foreach($transports as $key => $value)
                                        @if($value->product_type == '0')
                                            
                                                <section class="data">
                                                    <h4> {{$value->product_name}}</h4>
                                                </section>
                                                <div class="wall"></div>
                                                <section class="data">
                                                    <h4>{{$value->price}} جنية</h4>
                                                </section>
                                            
                                        @endif 
                                    @endforeach 
                                </section>
                                <!-- End One Row  -->
                            </article>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="content">
                        <p class="content__title">تكلفة نقل العلف</p>
                            <article class="table__transport container">
                                <section class="head">
                                    <h3 class="title">تكلفة نقل العلف</h3>
                                    <!-- Start Dropdown Item -->
                                    <section class="title title__categories">
                                        <select class="city2 select__categories">
                                        @foreach($cities as $key => $value)
                                            <option value="{{$value->id}}">{{$value->name}}</option>
                                        @endforeach 
                                        </select>
                                    </section>
                                    <!-- End Dropdown Item -->
                                </section>
                                <!-- Start One Row  -->
                                <section class="rows food">
                                    @foreach($transports as $key => $value)
                                        @if($value->product_type == '1')
                                            
                                            <section class="data">
                                                <h4> {{$value->product_name}}</h4>
                                            </section>
                                            <div class="wall"></div>
                                            <section class="data">
                                                <h4>{{$value->price}} جنية</h4>
                                            </section>
                                            
                                        @endif 
                                    @endforeach 
                                </section>
                                <!-- End One Row  -->
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        @endif 
        <!-- End Transport Cost -->


        <!-- Start Company Market -->
        @if(count($companies->LocalStockMember) !== 0 || count($companies->FodderStocks) !== 0)
        <article class="cards company-market">
            <h2 class="main-title">البورصات</h2>
            <!-- Start Slider -->
            <article class="market slider">
                <div class="container-fluid logos__holder">
                    <section class="market-slider" dir='rtl'>
                        @foreach($companies->LocalStockMember as $key => $value)
                            <div class="item">
                                <a href="{{ route('front_local_members',$value->Section->id) }}" class="logo-holder">
                                    <img src="{{asset('uploads/sections/sub/'.$value->Section->image)}}" alt="partner logo">
                                  
                                </a>
                                {{$value->Section->name}}
                            </div>
                        @endforeach 
                        @if(count($companies->FodderStocks) != 0)
                            @foreach($stocks as $key => $value)
                                <div class="item">
                                    <a href="{{ route('front_fodder_stocks',$value->subSection->id) }}" class="logo-holder">
                                        <img src="{{asset('uploads/sections/avatar/'.$value->subSection->image)}}" alt="partner logo">
                                       
                                    </a>
                                    {{$value->subSection->name}}
                                </div>
                            @endforeach 
                        @endif 
                    </section>
                </div>
            </article>
            <!-- End Slider -->
        </article>
        @endif 
        <!-- End Company Market -->
        @if(count($companies->Companygallary) != 0)
         <!-- Start Company Gallery -->
        <article class="cards company-gallery">
            <h2 class="main-title">الصور</h2>
            <!-- Start Slider -->
            <article class="gallery slider">
                <div class="container-fluid logos__holder">
                    <section class="gallery-slider" id="gallery-slider" dir='rtl'>
                        <!-- Beginning of For Loop .. Src = image src in loop // index = index of image in loop -->
                        <!-- Use index in onClick="sendIndexToPopUp(index)" -->
                        <!-- Don't forget to add those images in slider model in line 393 -->
                        @foreach($companies->Companygallary as  $value)
                            <div class="item">
                                <a data-toggle="modal" data-target="#gallery-big-slider{{$value->id}}" class="logo-holder"
                                onClick="sendIndexToPopUp({{$value->id}})">
                                    <img src="{{asset('uploads/gallary/avatar/'.$value->image)}}" alt="partner logo">
                                  
                                </a>
                                {{$value->name}}
                            </div>
                        @endforeach 
                    </section>
                </div>
            </article>
            <!-- End Slider -->
        </article>
        <!-- End Company Gallery -->

        <!-- Start Gallery Modal -->
        @foreach($companies->Companygallary as  $value)
            <div class="modal fade" id="gallery-big-slider{{$value->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                        <p class="modal__title">{{$value->name}}</p>
                            <button type="button" class="modal__close" data-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid popup-slider" id="modal-body-slides">
                                @foreach($value->CompanyAlboumImages as  $val)
                                    <img src="{{asset('uploads/company/alboum/'.$val->image)}}" alt="img">
                                @endforeach 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach 
        <!-- End Gallery Modal -->
        @endif 

        <article class="cards new__company__details">
                <div class="new__title">
                    <h2 class="main-title">بيانات الشركة</h2>
                </div>
                <div class="box__data">
                    <div class="box__title">
                        <span class="title__icon">
<svg fill="#000000" height="24px" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg"><path
        d="M0 0h24v24H0V0z" fill="none"/><path
        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zM7 9c0-2.76 2.24-5 5-5s5 2.24 5 5c0 2.88-2.88 7.19-5 9.88C9.92 16.21 7 11.85 7 9z"/><circle
        cx="12" cy="9" r="2.5"/></svg>
                        </span>
                        <p class="title">العنوان</p>
                    </div>
                    <div class="box__body">
                        <div class="box__element">
                            <p class="title">
                                 <span class="span__icon">
                                    <svg enable-background="new 0 0 24 24" fill="#000000" height="20px"
                                         viewBox="0 0 24 24" width="20px" xmlns="http://www.w3.org/2000/svg">
                                        <rect fill="none" height="24" width="24"/>
                                        <path d="M16,4c0-1.1,0.9-2,2-2s2,0.9,2,2s-0.9,2-2,2S16,5.1,16,4z M20.78,7.58C19.93,7.21,18.99,7,18,7c-0.67,0-1.31,0.1-1.92,0.28 C16.66,7.83,17,8.6,17,9.43V10h5V9.43C22,8.62,21.52,7.9,20.78,7.58z M6,6c1.1,0,2-0.9,2-2S7.1,2,6,2S4,2.9,4,4S4.9,6,6,6z M7.92,7.28C7.31,7.1,6.67,7,6,7C5.01,7,4.07,7.21,3.22,7.58C2.48,7.9,2,8.62,2,9.43V10h5V9.43C7,8.6,7.34,7.83,7.92,7.28z M10,4 c0-1.1,0.9-2,2-2s2,0.9,2,2s-0.9,2-2,2S10,5.1,10,4z M16,10H8V9.43C8,8.62,8.48,7.9,9.22,7.58C10.07,7.21,11.01,7,12,7 c0.99,0,1.93,0.21,2.78,0.58C15.52,7.9,16,8.62,16,9.43V10z M15,16c0-1.1,0.9-2,2-2s2,0.9,2,2s-0.9,2-2,2S15,17.1,15,16z M21,22h-8 v-0.57c0-0.81,0.48-1.53,1.22-1.85C15.07,19.21,16.01,19,17,19c0.99,0,1.93,0.21,2.78,0.58C20.52,19.9,21,20.62,21,21.43V22z M5,16 c0-1.1,0.9-2,2-2s2,0.9,2,2s-0.9,2-2,2S5,17.1,5,16z M11,22H3v-0.57c0-0.81,0.48-1.53,1.22-1.85C5.07,19.21,6.01,19,7,19 c0.99,0,1.93,0.21,2.78,0.58C10.52,19.9,11,20.62,11,21.43V22z M12.75,13v-2h-1.5v2H9l3,3l3-3H12.75z"/>
                                    </svg>
                                 </span>
                                <span>
                                         الفرع الرئيسي
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    <li class="single__element">
                                        <a class="element__content"
                                           href="https://maps.google.com/?q={{$companies->latitude}},{{$companies->longitude}}"
                                           rel="noreferrer" target="_blank">{{$companies->address}}</a>
                                    </li>
                                 
                                    
                                </ul>
                            </div>
                        </div>
                        @if(count($companies->Companyaddress) !== 0)
                            @if(count($address) > 0)
                                @foreach($address as $key => $value)
                                    @if($key == ADDRESS_TYPE_LAB)
                                        <div class="box__element">
                                            <p class="title">
                                            <span class="span__icon">
                                                    <i class="fas fa-flask"></i>
                                            </span>
                                                <span class="span__content">
                                                عناوين المعامل
                                            </span>
                                            </p>
                                            <div class="body">
                                                <ul class="body__list">
                                                    @foreach($value as $v)
                                                    <li class="single__element">
                                                        <a class="element__content"
                                                           href="https://maps.google.com/?q={{$v->latitude}},{{$v->longitude}}"
                                                           rel="noreferrer" target="_blank">{{$v->address}}
                                                        </a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @if($key == ADDRESS_TYPE_OUTLET)
                                        <div class="box__element">
                                            <p class="title">
                                        <span class="span__icon">
                                                <i class="fas fa-store-alt"></i>
                                        </span>
                                                <span class="span__content">
                                            عناوين المنافذ
                                        </span>
                                            </p>
                                            <div class="body">
                                                <ul class="body__list">
                                                    @foreach($value as $v)
                                                        <li class="single__element">
                                                            <a class="element__content"
                                                               href="https://maps.google.com/?q={{$v->latitude}},{{$v->longitude}}"
                                                               rel="noreferrer" target="_blank">{{$v->address}}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @if($key == ADDRESS_TYPE_FACTORY)
                                        <div class="box__element">
                                            <p class="title">
                                                <span class="span__icon">
                                                        <svg enable-background="new 0 0 24 24" fill="#000000" height="20px" viewBox="0 0 24 24" width="20px" xmlns="http://www.w3.org/2000/svg">
                                                            <g><rect fill="none" height="24" width="24"/></g>
                                                            <g><path d="M19.93,8.35l-3.6,1.68L14,7.7V6.3l2.33-2.33l3.6,1.68c0.38,0.18,0.82,0.01,1-0.36c0.18-0.38,0.01-0.82-0.36-1l-3.92-1.83 c-0.38-0.18-0.83-0.1-1.13,0.2L13.78,4.4C13.6,4.16,13.32,4,13,4c-0.55,0-1,0.45-1,1v1H8.82C8.4,4.84,7.3,4,6,4C4.34,4,3,5.34,3,7 c0,1.1,0.6,2.05,1.48,2.58L7.08,18H6c-1.1,0-2,0.9-2,2v1h13v-1c0-1.1-0.9-2-2-2h-1.62L8.41,8.77C8.58,8.53,8.72,8.28,8.82,8H12v1 c0,0.55,0.45,1,1,1c0.32,0,0.6-0.16,0.78-0.4l1.74,1.74c0.3,0.3,0.75,0.38,1.13,0.2l3.92-1.83c0.38-0.18,0.54-0.62,0.36-1 C20.75,8.34,20.31,8.17,19.93,8.35z M6,8C5.45,8,5,7.55,5,7c0-0.55,0.45-1,1-1s1,0.45,1,1C7,7.55,6.55,8,6,8z M11.11,18H9.17 l-2.46-8h0.1L11.11,18z"/></g>
                                                        </svg>
                                                </span>
                                                <span class="span__content">
                                                    عناوين المصنع
                                                </span>
                                            </p>
                                            <div class="body">
                                                <ul class="body__list">
                                                    @foreach($value as $v)
                                                        <li class="single__element">
                                                            <a class="element__content"
                                                               href="https://maps.google.com/?q={{$v->latitude}},{{$v->longitude}}"
                                                               rel="noreferrer" target="_blank">{{$v->address}}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @if($key == ADDRESS_TYPE_FARM)
                                        <div class="box__element">
                                            <p class="title">
                                            <span class="span__icon">
                                              <i class="fas fa-leaf"></i>
                                            </span>
                                                <span class="span__content">
                                                عناوين المزارع
                                            </span>
                                            </p>
                                            <div class="body">
                                                <ul class="body__list">
                                                    @foreach($value as $v)
                                                        <li class="single__element">
                                                            <a class="element__content"
                                                               href="https://maps.google.com/?q={{$v->latitude}},{{$v->longitude}}"
                                                               rel="noreferrer" target="_blank">{{$v->address}}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif

                                    @if($key == ADDRESS_TYPE_OTHER)
                                            <div class="box__element">
                                                <p class="title">
                                            <span class="span__icon">
                                              <i class="fas fa-ellipsis-h"></i>
                                            </span>
                                                    <span class="span__content">
                                                مقرات أخري
                                            </span>
                                                </p>
                                                <div class="body">
                                                    <ul class="body__list">
                                                        @foreach($value as $v)
                                                            <li class="single__element">
                                                                <a class="element__content"
                                                                   href="https://maps.google.com/?q={{$v->latitude}},{{$v->longitude}}"
                                                                   rel="noreferrer" target="_blank">{{$v->address}}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                @endforeach
                            @else
                                <div class="box__element">
                            <p class="title">
                                <span class="span__icon">
                                    <svg enable-background="new 0 0 24 24" fill="#000000" height="20px"
                                         viewBox="0 0 24 24" width="20px" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <rect fill="none" height="24" width="24"/>
                                    </g>
                                    <g>
                                        <path d="M19.93,8.35l-3.6,1.68L14,7.7V6.3l2.33-2.33l3.6,1.68c0.38,0.18,0.82,0.01,1-0.36c0.18-0.38,0.01-0.82-0.36-1l-3.92-1.83 c-0.38-0.18-0.83-0.1-1.13,0.2L13.78,4.4C13.6,4.16,13.32,4,13,4c-0.55,0-1,0.45-1,1v1H8.82C8.4,4.84,7.3,4,6,4C4.34,4,3,5.34,3,7 c0,1.1,0.6,2.05,1.48,2.58L7.08,18H6c-1.1,0-2,0.9-2,2v1h13v-1c0-1.1-0.9-2-2-2h-1.62L8.41,8.77C8.58,8.53,8.72,8.28,8.82,8H12v1 c0,0.55,0.45,1,1,1c0.32,0,0.6-0.16,0.78-0.4l1.74,1.74c0.3,0.3,0.75,0.38,1.13,0.2l3.92-1.83c0.38-0.18,0.54-0.62,0.36-1 C20.75,8.34,20.31,8.17,19.93,8.35z M6,8C5.45,8,5,7.55,5,7c0-0.55,0.45-1,1-1s1,0.45,1,1C7,7.55,6.55,8,6,8z M11.11,18H9.17 l-2.46-8h0.1L11.11,18z"/>
                                    </g>
                                </svg>
                                </span>
                                <span class="span__content">
                                    عناوين المصنع
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($companies->Companyaddress as  $value)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="https://maps.google.com/?q={{$value->latitude}},{{$value->longitude}}"
                                            rel="noreferrer" target="_blank">{{$value->address}}
                                            </a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="box__data">
                    <div class="box__title">
                        <span class="title__icon">
                            <svg fill="#000000" height="24px" viewBox="0 0 24 24" width="24px"
                                 xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path
                                    d="M22 3H2C.9 3 0 3.9 0 5v14c0 1.1.9 2 2 2h20c1.1 0 1.99-.9 1.99-2L24 5c0-1.1-.9-2-2-2zm0 16H2V5h20v14zm-2.99-1.01L21 16l-1.51-2h-1.64c-.22-.63-.35-1.3-.35-2s.13-1.37.35-2h1.64L21 8l-1.99-1.99c-1.31.98-2.28 2.37-2.73 3.99-.18.64-.28 1.31-.28 2s.1 1.36.28 2c.45 1.61 1.42 3.01 2.73 3.99zM9 12c1.65 0 3-1.35 3-3s-1.35-3-3-3-3 1.35-3 3 1.35 3 3 3zm0-4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm6 8.59c0-2.5-3.97-3.58-6-3.58s-6 1.08-6 3.58V18h12v-1.41zM5.48 16c.74-.5 2.22-1 3.52-1s2.77.49 3.52 1H5.48z"/></svg>
                        </span>
                        <p class="title">بيانات التواصل</p>
                    </div>
                    <div class="box__body">
                    @if($phones[0] !== null)
                        <div class="box__element">
                            <p class="title">
                                 <span class="span__icon">
<svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px" xmlns="http://www.w3.org/2000/svg"><path
        d="M0 0h24v24H0V0z" fill="none"/><path
        d="M6.54 5c.06.89.21 1.76.45 2.59l-1.2 1.2c-.41-1.2-.67-2.47-.76-3.79h1.51m9.86 12.02c.85.24 1.72.39 2.6.45v1.49c-1.32-.09-2.59-.35-3.8-.75l1.2-1.19M7.5 3H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.49c0-.55-.45-1-1-1-1.24 0-2.45-.2-3.57-.57-.1-.04-.21-.05-.31-.05-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.45-5.15-3.76-6.59-6.59l2.2-2.2c.28-.28.36-.67.25-1.02C8.7 6.45 8.5 5.25 8.5 4c0-.55-.45-1-1-1z"/></svg>
                                 </span>
                                <span>
                                        التلفون الأرضي
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($phones as $q)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="tel:{{$q}}"
                                            rel="noreferrer" target="_blank">{{$q}}</a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    @endif
                    @if($mobiles[0] !== null)
                        <div class="box__element">
                            <p class="title">
                                <span class="span__icon">
                                <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"
                                     xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path
                                        d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"/></svg>
                                </span>
                                <span class="span__content">
                                    الهاتف الجوال
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($mobiles as $m)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="tel:{{$m}}"
                                            rel="noreferrer" target="_blank">{{$m}} </a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        @endif
                        @if($faxs[0] !== null)
                        <div class="box__element">
                            <p class="title">
                                <span class="span__icon">
                                <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"
                                     xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path
                                        d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"/></svg>
                                </span>
                                <span class="span__content">
                                     الفاكس
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($faxs as $f)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="tel:{{$f}}"
                                            rel="noreferrer" target="_blank">{{$f}} </a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        @endif
                        @if($emails[0] !== null)
                        <div class="box__element">
                            <p class="title">
                                 <span class="span__icon">
<svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px" xmlns="http://www.w3.org/2000/svg"><path
        d="M0 0h24v24H0V0z" fill="none"/><path
        d="M22 6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6zm-2 0l-8 5-8-5h16zm0 12H4V8l8 5 8-5v10z"/></svg>                                 </span>
                                <span>
                                        البريد الإلكتروني
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($emails as $m)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="mailto:{{$m}}"
                                            rel="noreferrer" target="_blank">{{$m}}</a>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                        @endif
                        @if(count($companies->CompanySocialmedia) != 0)
                        <div class="box__element">
                            <p class="title">
                                <span class="span__icon">
                                <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"
                                     xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM4 12c0-.61.08-1.21.21-1.78L8.99 15v1c0 1.1.9 2 2 2v1.93C7.06 19.43 4 16.07 4 12zm13.89 5.4c-.26-.81-1-1.4-1.9-1.4h-1v-3c0-.55-.45-1-1-1h-6v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41C17.92 5.77 20 8.65 20 12c0 2.08-.81 3.98-2.11 5.4z"/></svg>   </span>
                                <span class="span__content">
                                    عناوين الشركة على السوشيال ميديا
                                </span>
                            </p>
                            <div class="body">
                                <div class="social__bar mb-3  text-center">

                                        @foreach($companies->CompanySocialmedia as $media)
                                            <a class="slider__nav__item website" href="{{$media->social_link}}"
                                            target="_blank">
                                            <img src="{{$media->Social->social_icon}}" style="width:40px;height:40px">
                                            </a>
                                        @endforeach

                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </article>
            <!-- End Company Details -->
        </section>
    </section>
</div>



        <!-- End Company Details -->
        @endif
        {{csrf_field()}}

    </section>
</section>

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
    <script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('Front_End/js/companies_details_popup_imgs.js')}}"></script>
<script src="{{asset('Front_End/js/companies_details.js')}}"></script>
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>

    <script src="{{asset('Front_End/js/rate_initialising.js')}}"></script>
    <script>
    $('select').niceSelect();
    const ratingResult = document.querySelector(".rating__result");
    const ratingNumber = document.querySelector(".customers_rate");
function ratingResultCallback(result) {
    ratingResult.textContent = `${result}/5`;
    //  and Then Do Any thing with the rating results
{{--@if(empty($rating))--}}
    var loggedIn = {{ Auth::guard('customer')->user() ? 'true' : 'false' }};
    $(document).on('click','.rating__star', function(){
    if(loggedIn == true){

        var data = {
            company_id : '{{$companies->id}}',
            reat : result,
            _token     : $("input[name='_token']").val()
        }

        $.ajax({
            url     : "{{ url('company-rating') }}",
            method  : 'post',
            data    : data,
            success : function(s,r){
                $('#msg-rate').show().delay(3000).fadeOut();
            }});
    }else{
        $('#msg-auth').show();
    }

    });
    if(loggedIn == true) {
        customerRate();
    }
}


executeRating(ratingStars, ratingResultCallback);

</script>

<script>
    function customerRate(){
            $.ajax({
                url: "{{ url('guide-customer-rate/'.$companies->id) }}",
                method: 'get',
                success: function (data) {
                    console.log(data);
                    result = parseInt(data);
                    if (data == 0) {
                        result = parseInt(data) + 1;
                    }
                    ratingNumber.textContent = result;
                }
            });
    }
</script>





<script type="text/javascript">



$(document).on('change','.city1', function(){

var data = {
city_id    : $(this).val(),
company_id : '{{$companies->id}}',
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-transports') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        $('.chek').html('')
        $('.chek').append(`
        `);
        $.each(s,function(k,v){
        $('.chek').append(`
            <section class="data">
                <h4> ${v.product_name}</h4>
            </section>
            <div class="wall"></div>
            <section class="data">
                <h4>${v.price} جنية</h4>
            </section>
        `);
    })
    }});

});

$(document).on('change','.city2', function(){

var data = {
city_id    : $(this).val(),
company_id : '{{$companies->id}}',
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-transports-fooder') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        $('.food').html('')
        $('.food').append(`
        `);
        $.each(s,function(k,v){
        $('.food').append(`
            <section class="data">
                <h4> ${v.product_name}</h4>
            </section>
            <div class="wall"></div>
            <section class="data">
                <h4>${v.price} جنية</h4>
            </section>
        `);
    })
    }});

});
</script>


</body>

</html>