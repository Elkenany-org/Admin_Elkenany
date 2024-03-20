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
    <title> {{$sections->name}}</title>
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
    <div class="page__tabs container">
        <ul class="tabs__list">
            <li class="list__item list__item-2">
                <a class="list__link active" href="{{ route('front_doctors',$sections->id) }}">الاستشاريون</a>
            </li>
            <li class="list__item list__item-2">
                <a class="list__link" href="{{ route('front_my_res',$sections->id) }}">مواعيدك</a>
            </li>
        </ul>
   
    </div>

    <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">{{$sections->name}}</h1>
        </div>
    </div>
    <!-- departments nav -->
    <!-- Start Search Box -->
    <article class="inner-search-box d-lg-block d-none d-sm-none f-md-none">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-2">
                    <i class="fas fa-star"></i>
                    <h4 class="title">الترتيب:</h4>
                    <select name="sort" class="sort">
                        <option value="" {{ isset($sort) && $sort == "" ? 'selected'  :'' }}>الكل</option>
                        <option value="1" {{ isset($sort) && $sort == "1" ? 'selected'  :'' }}>اعلى تقييمات</option>
                        <option value="3" {{ isset($sort) && $sort == "3" ? 'selected'  :'' }}>الاقل سعرا</option>
                        <option value="2" {{ isset($sort) && $sort == "2" ? 'selected'  :'' }}>الاعلى سعرا</option>
                    </select>
                </section>
                <section class="tabs tabs-2">
                    <i class="fas fa-user-md"></i>
                    <h4 class="title">المواعيد:</h4>
                    <select name="sort" class="sort">
                        <option value="" {{ isset($sort) && $sort == "" ? 'selected'  :'' }}>الكل</option>
                        <option value="4" {{ isset($sort) && $sort == "4" ? 'selected'  :'' }}>متاح</option>
                        <option value="5" {{ isset($sort) && $sort == "5" ? 'selected'  :'' }}>غير متاح</option>
                    </select>
                </section>
            </form>
        </div>
    </article>

    <section class="right col-lg-3 col-md-7 col-sm-12 d-lg-none d-block d-sm-block f-md-block">
        <section class="side__sticky">
            <h2 class="consultant__title" id="show-hide-accordion">
                حدد بحثك
                <i class="icon-s fas fa-search"></i>
            </h2>
            <div class="accordion" id="accordion">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                            <button aria-controls="collapseOne" aria-expanded="true" class="btn btn-link"
                                    data-target="#collapseOne" data-toggle="collapse">
                                <i class="fas fa-star"></i>
                                التقييم
                            </button>
                        </h5>
                    </div>
                    <div aria-labelledby="headingOne" class="collapse show" data-parent="#accordion" id="collapseOne">
                        <div class="card-body">
                            <ul>
                                <li>
                                    <a>
                                        <input id="tab-1-val-1" type="radio" value="" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-1-val-1">الكل</label>
                                    </a>
                                </li>
                              
                                <li>
                                    <a>
                                        <input id="tab-1-val-3" type="radio" value="1" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-1-val-3">اعلى تقييمات</label>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <input id="tab-1-val-4"  type="radio" value="2" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-1-val-4">الاقل سعرا</label>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <input id="tab-1-val-5" type="radio" value="3" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-1-val-5">الاعلى سعرا</label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button aria-controls="collapseTwo" aria-expanded="false" class="btn btn-link collapsed"
                                    data-target="#collapseTwo" data-toggle="collapse">
                                <i class="fas fa-user-md"></i>
                                المواعيد
                            </button>
                        </h5>
                    </div>
                    <div aria-labelledby="headingTwo" class="collapse" data-parent="#accordion" id="collapseTwo">
                        <div class="card-body">
                            <ul>
                                <li>
                                    <a>
                                        <input  id="tab-2-val-1" type="radio" value="" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-2-val-1">الكل</label>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <input id="tab-2-val-2" type="radio" value="4" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-2-val-2">متاح</label>
                                    </a>
                                </li>
                                <li>
                                    <a>
                                        <input id="tab-2-val-3" type="radio" value="5" name="sort" class="sort" {{ isset($sort) && $sort == "" ? 'checked'  :'' }}>
                                        <label for="tab-2-val-3">غير متاح</label>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- End Search Box -->
   <!-- Start Global Sections --> 
   <article class="container global__container">
        <section class="all-cards">
                <!-- Start One Card -->
                @if(!empty($doctors))
                    @foreach($doctors as $value)
                        <a href="{{ route('front_doctor',$value->id) }}" class="one-card">
                            <section class="top">
                                <section class="right-ppic">
                                    <img src="{{asset('uploads/doctors/avatar/'.$value->avatar)}}" alt="ppic">
                                </section>
                                <section class="left-content">
                                    <h2 class="main-title">د/{{$value->name}} </h2>
                                    <p>
                                    {{$value->certificates}}
                                    </p>
                                    <div class="rate-box">
                                        <div class="rating-readonly" data-rate-value="{{$value->rate}}"></div>
                                    </div>
                                    <section class="address-box">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="address">{{$value->adress}}</span>
                                    </section>
                                </section>
                            </section>
                            <section class="bottom">
                                <section class="availability-box">
                                    @if(count($value->DoctorServices) == count($value->DoctorOrders))
                                      <span class="availability unavailable">لا يوجد مواعيد متاحة</span>
                                    @endif
                                    @if(count($value->DoctorServices) > count($value->DoctorOrders))
                                        <span class="availability available">لديه مواعيد متاحة</span>
                                    @endif
                                </section>
                                <span class="more-details">احجز الان</span>
                            </section>
                        </a>


                       
                    @endforeach
                @endif
                <!-- End One Card -->
                <div class="row text-center">
                <div class="col-lg-12">
                    <ul class="pagination">
                        {{ $doctors->links() }}                    
                    </ul>
                </div>
                </div>
            <!-- End Consultants Cards -->
            <!-- End Content -->
          
        </section>
    </article>
    <!-- End Global Sections -->
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
    <!-- My JavaScript -->
    <script src="{{asset('Front_End/js/global.js')}}"></script>

    <script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>

    <script>
        $('select').niceSelect();

    $(document).on('change','.sort', function(){

    var link = "{{url('consultant/major/section',$sections->id)}}" + "?sort=" +$(this).val()
            window.location.href = link

    });
    </script>
</body>

</html>