
@extends('layouts.front')
@section('style')
<title>    البحث </title>
<style type="text/css">



</style>
@endsection
@section('content')


    <!-- End Partners -->
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container container" style="margin-bottom: 60px;
">
        <div class="custom__breadcrumb">
            <h1 class="page__title">البحث عن {{ $search }}</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

   
 
    <!-- Start Global Content -->
    <article class="global__container container">
        <div class="row">

        <!-- Start Left Section -->
        <section class="left col-lg-12 col-md-7 col-sm-12"  style="margin-bottom: 60px;
">
                    <section class="title-box">
                        <h2 class="title sects">الاقسام للدليل</h2>
                        <span class="sections-number">{{count($guidesubs)}} قسم</span>
                    </section>
                    <div class="new__cards row">
                        @foreach($guidesubs as $value)

                        <div class="col-12">
                            <div class="product__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/sections/avatar/'.$value->image)}}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ route('front_companies',$value->id) }}">
                                        {{$value->name}}
                                        </a>
                                    </header>
                                    <section class='product__info'>
                                        <p class="note">
                                        {{count($value->Company)}} {{$value->type}}
                                        </p>
                                    </section>
                                </div>
                               
                            </div>
                        </div>
                        
                        @endforeach
{{--                            @foreach($companies as $com)--}}

{{--                            @foreach($com->SubSections as $value)--}}

{{--                                <div class="col-12">--}}
{{--                                    <div class="product__card regular__hover">--}}
{{--                                        <div class="image__card">--}}
{{--                                            <img alt='product__image'--}}
{{--                                                 src="{{asset('uploads/sections/avatar/'.$value->image)}}"/>--}}
{{--                                        </div>--}}
{{--                                        <div class="product__content">--}}
{{--                                            <header class="product__title">--}}
{{--                                                <a href="{{ route('front_companies',$value->id) }}">--}}
{{--                                                    {{$value->name}}--}}
{{--                                                </a>--}}
{{--                                            </header>--}}
{{--                                            <section class='product__info'>--}}
{{--                                                <p class="note">--}}
{{--                                                    {{count($value->Company)}} {{$value->type}}--}}
{{--                                                </p>--}}
{{--                                            </section>--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
{{--                                </div>--}}

{{--                            @endforeach--}}

{{--                            @endforeach--}}
                    </div>
                </section>
                <!-- End Left Section -->

                <!-- Start Left Section -->
                <section class="left col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
                    <section class="title-box">
                        <h2 class="title"> الشركات</h2>
                        <span class="sections-number">{{count($companies)}} شركة</span>
                    </section>
                    <div class="new__cards row">

                     
                        @foreach($companies as $value)

                        <div class="col-12">
                            <div class="product__card big__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/company/images/'.$value->image)}}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ route('front_company',$value->id) }}">
                                        {{$value->name}}
                                        </a>
                                    </header>
                                    <div class="product__rating">
                                        <div class='star'></div>
                                        <div class='star'></div>
                                        <div class='star'></div>

                                        <div class='star'></div>
                                    </div>

                                        <section class='product__info'>
                                            @foreach($value->SubSections as $v)
                                                    <a class="note" href="{{ route('front_companies',$v->id) }}">&nbsp;&nbsp;<i class="fas fa-circle" style="font-size: 10px"></i>&nbsp;{{$v->name}}&nbsp;
                                                    </a>

                                            @endforeach

                                        </section>

                                    <section class='product__info'>
                                        <p class="note text-center">
                                        {{ str_limit($value->short_desc, $limit = 50, $end = '...') }}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address">{{$value->address}}</span>
                                        </section>
                                        <a class="more__details" href="{{ route('front_company',$value->id) }}">اعرف أكتر</a>
                                    </section>
                                </div>
                            
                            </div>
                        </div>

                        @endforeach
                        
                    </div>
                          <!-- End Left Section -->
                {{csrf_field()}}
                </section>

                     <!-- Start Left Section -->
                    <section class="main__left__container col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
                        <section class="title-box">
                            <h2 class="title sects"> البورصات</h2>
                        </section>
                        <div class="new__cards row">
                            @if(!is_null($localsubs))
                                @foreach($localsubs as $value)

                                <div class="col-12">
                                    <div class="product__card">
                                        <div class="image__card">
                                            <img alt='product__image'
                                                src="{{asset('uploads/sections/sub/'.$value->image)}}"/>
                                        </div>
                                        <div class="product__content">
                                            <header class="product__title">
                                                <a href="{{ route('front_local_members',$value->id) }}">  {{$value->name}}</a></header>
                                        </div>
                                      
                                    </div>
                                </div>

                                @endforeach
                            @endif
                            @if(!is_null($foddersubs))
                                @foreach($foddersubs as $value)
                                <div class="col-12">
                                    <div class="product__card"> 
                                        <div class="image__card">
                                            <img alt='product__image'
                                                src="{{asset('uploads/sections/avatar/'.$value->image)}}"/>
                                        </div>
                                        <div class="product__content">
                                            <header class="product__title">
                                                <a href="{{ route('front_fodder_stocks',$value->id) }}">  {{$value->name}}</a></header>
                                        </div>
                                       
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                <!-- End Left Section -->

                <section class="left col-lg-12 col-md-7 col-sm-12" style="margin-bottom: 60px;
">
                <section class="title-box">
                    <h2 class="title sects"> المجلات</h2>
                    <span class="sections-number">{{count($magazines)}} مجلة</span>
                </section>
                <div class="new__cards row">
                                        
                    @foreach($magazines as $value)
                    <div class="col-12">
                            <div class="product__card big__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/magazine/images/'.$value->image)}}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ route('front_magazine',$value->id) }}">
                                        {{$value->name}}
                                        </a>
                                    </header>
                                    <div class="product__rating">
                                        <div class='star'></div>
                                        <div class='star'></div>
                                        <div class='star'></div>
                                        <div class='star'></div>
                                    </div>
                                    <section class='product__info'>
                                        <p class="note text-center">
                                        {{ str_limit($value->short_desc, $limit = 50, $end = '...') }}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address">{{$value->address}}</span>
                                        </section>
                                        <a class="more__details" href="{{ route('front_magazine',$value->id) }}">اعرف أكتر</a>
                                    </section>
                                </div>
                            
                            </div>
                        </div>
                    @endforeach
                    
                </div>
          

            </section>

             <!-- Start Left Section -->
             <section class="main__left__container col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
             <section class="title-box">
                    <h2 class="title sects"> الاخبار</h2>
                    <span class="sections-number">{{count($news)}} خبر</span>
                </section>
                <div class="new__cards row">
                                        
                    @foreach($news as $value)

                    <div class="col-12">
                        <div class="product__card  regular__hover">
                            <div class="image__card">
                                <img alt='product-image'
                                src="{{asset('uploads/news/avatar/'.$value->image)}}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_one_news',$value->id) }}">
                                    {{$value->title}}
                                    </a>
                                </header>
                                <section class="product__bottom justify-content-end">
                                    <section class="date__box">
                                        <span class="date">{{$value->created_at}}</span>
                                    </section>
                                </section>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    
                </div>
                

            </section>

            <section class="left col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
            <section class="title-box">
                    <h2 class="title sects"> المعارض</h2>
                    <span class="sections-number">{{count($showes)}} معرض</span>
                </section>
                <div class="new__cards row">
                                        
                    @foreach($showes as $value)

                    <div class="col-12">
                        <div class="featured__card__container">
                            <div class="product__card super__ultra__card regular__hover">
                             
                                <div class="image__card">
                                    <img alt='product__image'
                                         src="{{asset('uploads/show/images/'.$value->image)}}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ route('front_one_show',$value->id) }}">
                                        {{$value->name}}
                                        </a>
                                    </header>
                                    <div class="product__rating">
                                        <div class='star'></div>
                                        <div class='star'></div>
                                        <div class='star'></div>
                                        <div class='star'></div>
                                    </div>
                                    <section class='product__info'>
                                        <p class="big__note text-center text-center">
                                        {{ str_limit($value->desc, 50) }}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="far fa-calendar-alt"></i>
                                          
                                            <span class="content">
                                            @foreach($value->time() as $val)
                                               {{$val}} :
                                            @endforeach
                                            </span>
                                        </section>
                                        <section class="content__box">
                                            <i class="fas fa-users"></i>
                                            <span class="content"> {{$value->view_count}} متابع</span>
                                        </section>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="content">مصر -  {{$value->City->name}}</span>
                                        </section>
                                        <a class="more__details" href="{{ route('front_one_show',$value->id) }}">مهتم</a>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    
                </div>
               

            </section>

            <section class="main__left__container col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
            <section class="title-box">
                    <h2 class="title sects"> السوق</h2>
                    <span class="sections-number">{{count($stores)}} اعلان</span>
                </section>
                <div class="new__cards row">
                    @foreach($stores as $value)

                    <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                            @if(count($value->StoreAdsimages) > 0)
                                <img alt='product__image'
                                     src="{{asset('uploads/stores/alboum/'.$value->StoreAdsimages->first()->image)}}"/>
                            @endif
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_ads_detials',$value->id) }}">
                                    {{$value->title}}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note text-center">
                                    {{$value->salary}} جنية
                                    </p>
                                </section>
                                <section class="product__bottom">
                                    <section class="address-box">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="address">{{$value->address}}</span>
                                    </section>
                                    <a class="more__details" href="{{ route('front_ads_detials',$value->id) }}">اعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>
                       
                    @endforeach

                     <!-- End One Card -->

                </div>
             
            </section>

            <section class="left col-lg-12 col-md-12 col-sm-12" style="margin-bottom: 60px;
">
                <section class="title-box">
                    <h2 class="title sects"> الاستشاريون</h2>
                    <span class="sections-number">{{count($doctors)}} إستشاري </span>
                </section>

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
               
        </section>
            </section>

          

                   
          
          
        </div>
    
    
    </article>
    <!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>



@endsection