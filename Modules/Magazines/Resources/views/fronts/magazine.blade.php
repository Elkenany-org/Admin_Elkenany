
@extends('layouts.front')
@section('style')
<title>     {{$magazines->name}}</title>
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
@endsection
@section('content')

<section class="container global__container">
    <section class="company__details__container">
       @if($magazines->paied === 0)
        <!-- Start Company Info  -->
        <article class="cards company-info">
            <h2 class="main-title"> {{$magazines->name}}</h2>
            <section class="image-holder">
                <img class="company-image" src="{{asset('uploads/magazine/images/'.$magazines->image)}}" alt="card image">
            </section>
            <p>
            {{$magazines->short_desc}}
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
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                    </div>
                    <div class="mx-2">
                        <span class="rating__result">0/5</span>
                    </div>
                </div>
            @endif
            <p class="raters-number" style="display: block;"><span class="customers_rate"> {{count($magazines->MagazineRate)}}</span> عميل</p>
           
        </article>
        <!-- End Company Info  -->

        <!-- Start About Company -->
        <article class="cards about-company">
            <h2 class="main-title">عن المجلة</h2>
            <p>
            {{$magazines->about}}
            </p>
        </article>
        <!-- End About Company -->
        @endif
        @if($magazines->paied === 1 || Auth::guard('customer')->user()->memb == '1')
          <!-- Start Company Info  -->
          <article class="cards company-info">
            <h2 class="main-title"> {{$magazines->name}}</h2>
            <section class="image-holder">
                <img class="company-image" src="{{asset('uploads/magazine/images/'.$magazines->image)}}" alt="card image">
            </section>
            <p>
            {{$magazines->short_desc}}
            </p>
            @if(!empty($rating))
                

                <div class="main__rating d-flex align-items-center justify-content-center">
                    <div class="global__rating">
                        @for($i=0;$i<round($rating->rate);$i++)
                            <i id="rating__star" class="rating__star fas fa-star"></i>
                        @endfor
                        @for($i=0;$i<5-round($rating->rate);$i++)
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
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                        <i id="rating" class="rating rating__star far fa-star"></i>
                    </div>
                    <div class="mx-2">
                        <span class="rating__result">0/5</span>
                    </div>
                </div>
            @endif
              <p class="raters-number" style="display: block;">
                  <span id="msg-rate" style="display: none"><small>تم التقييم بنجاح</small><br></span>
                  <span id="msg-auth" style="display: none"><small> برجاء<a href="/customer/login"> تسجيل الدخول</a> </small><br></span>
                  <span class="customers_rate">{{count($magazines->MagazineRate)}} </span> عميل</p>
           
        </article>
        <!-- End Company Info  -->

        <!-- Start About Company -->
        <article class="cards about-company">
            <h2 class="main-title">عن المجلة</h2>
            <p>
            {{$magazines->about}}
            </p>
        </article>
        <!-- End About Company -->
        @if(count($magazines->Magazinguide) !== 0)
        <!-- Start Company Products -->
        <article class="cards company-products">
            <h2 class="main-title">الدلائل</h2>
            <!-- Start Slider -->
            <article class="products slider">
                <div class="container-fluid holder">
                    <section class="products-slider" dir='rtl'>
                        @foreach($magazines->Magazinguide as $key => $value)
                            <div class="item">
                                <a class="logo-holder"  target="_blank" href="{{$value->link}}">
                                    <img src="{{asset('uploads/magazine/guides/'.$value->image)}}" alt="partner logo">
                                    {{$value->name}}
                                </a>
                            </div>
                        @endforeach 
                    </section>
                </div>
            </article>
            <!-- End Slider -->
        </article>
        @endif
        <!-- End Company Products -->

        

        @if(count($magazines->Magazingallary) !== 0)
         <!-- Start Company Gallery -->
         <article class="cards company-gallery">
            <h2 class="main-title">الصور</h2>
            <!-- Start Slider -->
            <article class="gallery slider">
                <div class="container-fluid holder">
                    <section class="gallery-slider" id="gallery-slider" dir='rtl'>
                        <!-- Beginning of For Loop .. Src = image src in loop // index = index of image in loop -->
                        <!-- Use index in onClick="sendIndexToPopUp(index)" -->
                        <!-- Don't forget to add those images in slider model in line 393 -->
                        @foreach($magazines->Magazingallary as  $value)
                            <div class="item">
                                <a data-toggle="modal" data-target="#gallery-big-slider{{$value->id}}" class="logo-holder"
                                onClick="sendIndexToPopUp({{$value->id}})">
                                    <img src="{{asset('uploads/gallary/avatar/'.$value->image)}}" alt="partner logo">
                                    {{$value->name}}
                                </a>
                            </div>
                        @endforeach 
                    </section>
                </div>
            </article>
            <!-- End Slider -->
        </article>
        <!-- End Company Gallery -->
        @endif
        <!-- Start Gallery Modal -->
        @foreach($magazines->Magazingallary as  $value)
            <div class="modal fade" id="gallery-big-slider{{$value->id}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4>{{$value->name}}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="fas fa-times-circle"></i></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid popup-slider" id="modal-body-slides">
                                @foreach($value->MagazineAlboumImages as  $val)
                                    <img src="{{asset('uploads/magazine/alboum/'.$val->image)}}" alt="img">
                                @endforeach 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach 
        <!-- End Gallery Modal -->

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
                                        عناوين الإدارة
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    <li class="single__element">
                                        <a class="element__content"
                                           href="https://maps.google.com/?q={{$magazines->address}}"
                                           rel="noreferrer" target="_blank">{{$magazines->address}}</a>
                                    </li>
                                 
                                    
                                </ul>
                            </div>
                        </div>
                        @if(count($magazines->Magazineaddress) !== 0)
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
                                    عنوان إضافي
                                </span>
                            </p>
                            <div class="body">
                                <ul class="body__list">
                                    @foreach($magazines->Magazineaddress as  $value)
                                        <li class="single__element">
                                            <a class="element__content"
                                            href="https://maps.google.com/?q={{$value->address}}"
                                            rel="noreferrer" target="_blank">{{$value->address}}
                                            </a>
                                        </li>
                                    @endforeach 
                                    
                                </ul>
                            </div>
                        </div>
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
                        @if(count($magazines->MagazinSocialmedia) != 0)
                        <div class="box__element">
                            <p class="title">
                                <span class="span__icon">
                                <svg fill="#000000" height="20px" viewBox="0 0 24 24" width="20px"
                                     xmlns="http://www.w3.org/2000/svg"><path d="M0 0h24v24H0V0z" fill="none"/><path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM4 12c0-.61.08-1.21.21-1.78L8.99 15v1c0 1.1.9 2 2 2v1.93C7.06 19.43 4 16.07 4 12zm13.89 5.4c-.26-.81-1-1.4-1.9-1.4h-1v-3c0-.55-.45-1-1-1h-6v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41C17.92 5.77 20 8.65 20 12c0 2.08-.81 3.98-2.11 5.4z"/></svg>   </span>
                                <span class="span__content">
                                    عناوين الشركة على الإنترنت
                                </span>
                            </p>
                            <div class="body">
                                <div class="social__bar mb-3  text-center">
                                        @foreach($magazines->MagazinSocialmedia as $media)
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


@endsection

@section('script')
<script src="{{asset('Front_End/js/companies_details_popup_imgs.js')}}"></script>
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('Front_End/js/companies_details.js')}}"></script>
<script src="{{asset('Front_End/js/rate_initialising.js')}}"></script>
<script>
    $('select').niceSelect();
    const ratingResult = document.querySelector(".rating__result");
    const ratingNumber = document.querySelector(".customers_rate");
</script>
{{--@if(Auth::guard('customer')->user())--}}

<script>
$(document).ready(function(){


    function ratingResultCallback(result) {
        ratingResult.textContent = `${result}/5`;
        var loggedIn = {{ Auth::guard('customer')->user() ? 'true' : 'false' }};

        //  and Then Do Any thing with the rating results

        $(document).on('click','.rating__star', function(){
            if(loggedIn == true) {
                console.log('clicked')
                var data = {
                    maga_id: '{{$magazines->id}}',
                    reat: result,
                    _token: $("input[name='_token']").val()
                }

                $.ajax({
                    url: "{{ url('magazines-rating') }}",
                    method: 'post',
                    data: data,
                    success: function (s, r) {
                        $('#msg-rate').show().delay(3000).fadeOut();
                    }
                });
            }else{
                $('#msg-auth').show();
            }
        });

        if(loggedIn == true) {
            customerRate();
        }
    }

    function customerRate(){
        $.ajax({
            url     : "{{ url('magazines-customer-rating/'.$magazines->id) }}",
            method  : 'get',
            success : function(data){
                console.log(data);
                result = parseInt(data);
                if(data == 0){
                    result = parseInt(data) + 1;
                }
                ratingNumber.textContent = result;
            }});
    }
    executeRating(ratingStars, ratingResultCallback);
})


</script>

{{--@endif--}}
<script type="text/javascript">

</script>
@endsection