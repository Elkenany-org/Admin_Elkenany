@extends('layouts.front')
    @section('style')
    <title>   اعلاناتي</title>
    @endsection
@section('content')
<!-- Start Header -->
<!-- Start Header -->
<header class="banner__header">      
    <div class="container-fluid one-full-slider-banner">
        @foreach($banners as $ad)
            @if($ad->type == 'banner')
            <a href="{{$ad->link}}" target="_blank"><img alt="banner" class="banner" src="{{$ad->image_url}}"></a>
            @endif
        @endforeach 
    </div>
</header>

<article class="partners slider my-4 mt-5">
        <div class="container-fluid logos__holder ">

            <section class="partners-slider" dir='rtl'>
            @foreach($banners as $logo)
                @if($logo->type == 'logo')
                    <div class="item">
                    <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{$logo->image_url}}"></a>
                    <div class="wall"></div>
                    </div>
                @endif
            @endforeach 
             
            </section>
        </div>
    </article>

<!-- departments nav -->
<div class="page__tabs nb-0 container">
        <ul class="tabs__list">
        <li class="list__item list__item-3">
            <a class="list__link " href="{{ route('front_section_store',$section->type) }}">السوق</a>
        </li>
        <li class="list__item list__item-3">
            <a  class="list__link active" href="{{ route('front_my_ads',$section->id) }}">اعلاناتك</a>
        </li>
        <li class="list__item list__item-3">
        @if(Auth::guard('customer')->user())
            <a  class="list__link " href="{{ route('front_chats',$section->id) }}">الرسائل</a>
        @else
            <a  class="list__link " href="{{ route('customer_login') }}">الرسائل</a>
        @endif
        </li>
    </ul>
</div>
<!-- departments nav -->
<!-- Start Global Content -->
<article class="global__container mt-0 container">
    <div class="row">
        @if(Auth::guard('customer')->user())
            <a class="action__yellow__green__button w-100" href="{{ route('front_add_ads',$section->id) }}">+ أضف إعلانك</a>

        @else
            <a class="action__yellow__green__button w-100" href="{{ route('customer_login') }}">+ أضف إعلانك</a>

        @endif
        <!-- Start Left Section -->

            <div class="new__cards p-0 my-2 row  w-100">
            
                @if(Auth::guard('customer')->user())
                    @if(!empty($ads))
                        @foreach($ads as $value)
                       

                        <div class="col-12  w-100">
                        <form action="{{route('front_delete_store_ads')}}" class="form{{$value->id}}" method="post">
                                {{csrf_field()}}
                            <div class="product__card ultra__card regular__hover">

                                <div class="image__card">
                                    @if(count($value->StoreAdsimages) > 0)
                                    <img alt='product__image' src="{{$value->StoreAdsimages->first()->image_url }}"/>
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
                                            <i class="fas fa-calendar"></i>
                                            <span class="address"> {{date('Y-m-d',strtotime($value->created_at))}}</span>
                                            <br>
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address">{{$value->address}}</span>
                                        </section>
                                        <a class="more__details" href="{{ route('front_ads_detials',$value->id) }}">اعرف أكتر</a>
                                    </section>
                                </div>
                                <div class="market__dropdown">
                                    <button aria-expanded="false" aria-haspopup="true"
                                            class="dropdown-toggle menu" data-toggle="dropdown"
                                            id="dropdownMenuButton" type="button">
                                    </button>
                                    <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('front_edit_ads',$value->id) }}">تعديل الاعلان</a>
                                        <a class="dropdown-item" href="paid_advertisement.html">اعلان
                                            مدفوع</a>
                                            <input type="hidden" name="id" value="{{$value->id}}">
                                        <a class="dropdown-item trigger" id="sub" data-id="{{$value->id}}">حذف الاعلان</a>
                                    </div>
                                </div>
                            </div>

                                    <!-- Start POPUP -->
                        <article class="popup-overlay popup-overlay{{$value->id}}" data=" " id="popup-overlay">
                            <div class="popup-transport" id="popup">
                                <div class="popup-close" id="close"><i class="fas fa-times"></i></div>
                                <section class="popup-content">
                                    <h3 class="popup-title danger__title">حذف الاعلان</h3>
                                    <p class="popup-description">
                                        سيتم حذف الاعلان، هل تريد الاستمرار
                                    </p>
                                </section>
                                <input id="submit" class="submit action__yellow__green__button w-50" type="submit" value=" استمرار">
                                
                            </div>
                        </article>
                        </form>
                        
                        </div>

                        
                            <!-- End POPUP -->
                            <!-- End One Card -->
                        @endforeach
                    @endif
                @endif
           
            </div>
        <!-- End Left Section -->
    </div>
</article>
<!-- End Global Content -->

@endsection

@section('script')

<script>
    let mainContainer = $("#popup-overlay");

    // open and close popup regularly
    $(document).ready(function () {
        $('.trigger').click(function () {
            var id = $(this).data('id');
            console.log(id);
           
            $(".popup-overlay"+id).fadeIn(300);
        });

        $('#close').click(function () {
            mainContainer.fadeOut(300);
        });
    });

    // close popup if click away from it
    $(document).mouseup(function (e) {
        if (mainContainer.has(e.target).length === 0) {
            mainContainer.fadeOut(300);
        }
    });

</script>

@endsection