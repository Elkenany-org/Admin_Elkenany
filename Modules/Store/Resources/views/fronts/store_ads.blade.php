@extends('layouts.front')
    @section('style')
    <title> اعلانات السوق</title>
    <!-- My CSS -->
    <link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
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
                @if($ad->type == 'banner')
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
            <a class="list__link active" href="{{ route('front_section_store',$section->type) }}">السوق</a>
        </li>
        <li class="list__item list__item-3">
            <a  class="list__link " href="{{ route('front_my_ads',$section->id) }}">إعلاناتك</a>
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


<div class="breadcrumb__container nt-0 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">سوق الكناني</h1>
        </div>
    </div>
<!-- Start Search Box -->
<article class="inner-search-box d-lg-block d-none">
    <div class="container">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
            <div class="edge"></div>
                <section class="tabs tabs-4">
                    <i class="fas  fa-search"></i>
                    <h4 class="title">بحث:</h4>
                    <input class="search__input" name="keyword" value="{{isset($_GET['keyword']) ? $_GET['keyword'] : ''}}" id="searchInput" placeholder="بحث" type="search">
                    <button class="btn" formaction="" formmethod="GET" type="submit" style="background-color: #1a5302;color: #fff;border-radius: 6px 0px 0px 6px;position: absolute;left: 10px;padding-bottom: 8px"><i class="fas  fa-search text-light"></i></button>
                </section>
            <section class="tabs tabs-4">
            <i class="fas fa-puzzle-piece"></i>
                <h4 class="title">القطاع:</h4>
{{--                slecs onChange="window.location.href=this.value" value="{{ route('front_section_store',$value->type) }}"--}}
                <select class="form-control section" >
                    @foreach($secs as $value)
                    <option  data="{{$value->id}}"  value="{{$value->type}}" {{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs tabs-4">
                <i class="fas fa-puzzle-piece"></i>
                <h4 class="title">الترتيب:</h4>
                <select name="sort" class="form-control sort">
                    @foreach(config('store.sort_store') as $k => $v)
                    <option value="{{$k}}" {{ isset($_GET['sort']) && $_GET['sort'] == $k ? 'selected'  :'' }}> {{$v}}</option>
                    @endforeach
                </select>
            </section>
            <section class="tabs tabs-4">
                <i class="far fa-calendar-alt"></i>
                <h4 class="title">التاريخ:</h4>
                <input class="date" type="date" value="{{isset($_GET['date']) ? $_GET['date'] : ""}}">
            </section>

        </form>
    </div>
</article>
@include('../parts.alert')
<!-- End Search Box -->
<!-- Start Global Content -->
<article class="global__container container">
    <div class="row">

            <!-- Start Right Section -->
            <section class="right main__right__container col-lg-4 col-md-12 col-sm-12">
                <section class="sidebar__sticky">
                <h2 id="show-hide-accordion" class="main-title">
                    حدد بحثك
                    <i class="icon-s fas fa-search"></i>
                </h2>
                <div id="accordion" class="accordion">
                <div class="card">
                            <div class="card-header" id="headingSearch">
                                <h5 class="mb-0">
                                    <button aria-controls="collapseSearch" aria-expanded="false"
                                            class="btn btn-link collapsed" data-target="#collapseSearch"
                                            data-toggle="collapse">
                                        <i class="fas fa-search"></i>
                                        البحث
                                    </button>
                                </h5>
                            </div>
                            <div aria-labelledby="headingSearch" class="collapse show" data-parent="#accordion" id="collapseSearch">
                                <form action="" method="GET">
                                    <div class="card-body">
{{--                                        ser--}}
                                        <input class="search__input" name="keyword" value="{{isset($_GET['keyword']) ? $_GET['keyword'] : ''}}" placeholder="بحث" type="search">
                                        <button class="btn" type="submit" style="background-color: #1a5302;color: #fff;border-radius: 6px 0px 0px 6px">بحث</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-puzzle-piece"></i>
                                    القطاع
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                             data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                @foreach($secs as $value)
                                    <li>
                                        <a>
{{--                                            sec--}}
                                            <input type="radio" id="tab-1-val-{{$value->id}}"  class="section" name="tab-1-values" value="{{$value->type}}"
                                            {{ isset($section) && $section->id == $value->id ? 'checked'  :'' }}>
                                            <label for="tab-1-val-{{$value->id}}">{{$value->name}}</label>
                                        </a>
                                    </li>
                                @endforeach  
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                </section>
            </section>
            <!-- End Right Section -->
            <!-- Start Left Section -->
            <section class="main__left__container col-lg-8 col-md-12 col-sm-12">
                <div class="new__cards row">
                @foreach($storesmemb as $value)

                    <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                            @if(count($value->StoreAdsimages) > 0)
                                <img alt="product__image" src="{{ $value->StoreAdsimages ? $value->StoreAdsimages->first()->image_url : "" }}"/>
                            @endif
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_ads_detials',$value->id) }}">{{$value->title}}</a>
                                </header>
                                <section class='product__info'>
                                    <p class="note text-center">{{$value->salary}} جنية</p>
                                </section>
                                <section class="product__bottom">
                                    <section class="address-box">
                                        <i class="fas fa-calendar"></i>
                                        <span class="address"> {{date('Y-m-d',strtotime($value->created_at))}}</span>
                                        <br>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="address">{{$value->address}}</span>
                                    </section>
                                    <a class="more__details" href="{{ route('front_ads_detials',$value->id) }}">أعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>
                    
                    @endforeach
                    @foreach($storesa as $value)

                    <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                            @if(count($value->StoreAdsimages) > 0)
                                <img alt="product__image" src="{{$value->StoreAdsimages ? $value->StoreAdsimages->first()->image_url : "" }}"/>
                            @endif
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_ads_detials',$value->id) }}">{{$value->title}}</a>
                                </header>
                                <section class='product__info'>
                                    <p class="note text-center">{{$value->salary}} جنية</p>
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
                        </div>
                    </div>
                    
                    @endforeach
                    @foreach($stores as $value)

                    <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                            @if(count($value->StoreAdsimages) > 0)
                                <img alt="product__image" src="{{$value->StoreAdsimages ? $value->StoreAdsimages->first()->image_url : "" }}"/>
                            @endif
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_ads_detials',$value->id) }}">{{$value->title}}</a>
                                </header>
                                <section class='product__info'>
                                    <p class="note text-center">{{$value->salary}} جنية</p>
                                </section>
                                <section class="product__bottom">
                                    <section class="address-box">
                                        <i class="fas fa-calendar"></i>
                                        <span class="address"> {{date('Y-m-d',strtotime($value->created_at))}}</span>
                                        <br>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="address">{{$value->address}}</span>

                                    </section>
{{--                                    <section class="address-box">--}}
{{--                                        <i class="fas fa-map-marker-alt"></i>--}}
{{--                                        <span class="address">{{$value->created_at}}</span>--}}
{{--                                    </section>--}}

                                    <a class="more__details" href="{{ route('front_ads_detials',$value->id) }}">اعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>
                       
                    @endforeach

                     <!-- End One Card -->

                </div>
                <div class="row pagination__container">
                        <div class="col-12">
                        {{$stores->links()}}
                        </div>
                    </div>
            </section>
            <!-- End Left Section -->
            {{csrf_field()}}
    </div>
</article>
<!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>


<script>
    function dateFormat(inputDate, format) {
        //parse the input date
        const date = new Date(inputDate);

        //extract the parts of the date
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();

        //replace the month
        format = format.replace("MM", month.toString().padStart(2,"0"));

        //replace the year
        if (format.indexOf("yyyy") > -1) {
            format = format.replace("yyyy", year.toString());
        } else if (format.indexOf("yy") > -1) {
            format = format.replace("yy", year.toString().substr(2,2));
        }

        //replace the day
        format = format.replace("dd", day.toString().padStart(2,"0"));

        return format;
    }
</script>

<script>
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };

    $(document).on('change','.section',function (){
        var type = this.value;

        var url      = window.location.href;
        var origin   = window.location.origin;

        var _array = url.split('/'),
            _foo = _array[_array.length-2];

        var new_url = origin +'/'+ _foo +'/'+ type;
        var sort = getUrlParameter('sort');
        if(sort){
            return   window.location.href = new_url+'?sort='+sort;
        }
        return window.location.href = new_url;
    })
</script>
<script>
 
    $('select').niceSelect();
    
$(document).on('change','.sort', function(){

var link = "{{url('store-section',$section->type)}}" + "?sort=" +$(this).val()
        window.location.href = link

});


    $(document).on('change','.date', function(){
        var link = "?sort=" +$('.sort').val() + "&date=" +$(this).val();
        setInterval(() => {
            window.location.href = link
            }, 3000);
    });

$(document).on('change','.sec', function(){
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-store-search') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
        console.log(s);
        $('.slecs option').each(function(){
            
            if($(this).attr('data') != s.id){
                $(this).removeAttr('selected');
            }else{
                $(this).attr('selected', true);

                $('select').niceSelect('update');


            }
        })
		$.each(s.datas,function(k,v){
            const date = new Date(v.created_at);
            if(v.store_adsimages.length > 0){
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                                <img alt='product__image'
                                     src="{{asset('uploads/stores/alboum/')}}/${v.store_adsimages.slice(0)[0].image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('store-ads-detials') }}/${v.id}">
                                    ${v.title}
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
                                        <span class="address"> ${dateFormat(v.created_at, 'yyyy-MM-dd') }</span>
                                        <br>
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span class="address">${v.address}</span>
                                    </section>
                                    <a class="more__details" href="{{ url('store-ads-detials') }}/${v.id}">اعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>

        
		`);}else{
            $('.new__cards').append(`

<div class="col-12">
                <div class="product__card big__card regular__hover">
                    <div class="image__card">
                     
                    </div>
                    <div class="product__content">
                        <header class="product__title">
                            <a href="{{ url('store-ads-detials') }}/${v.id}">
                            ${v.title}
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
                                <span class="address">${v.address}</span>
                            </section>
                            <a class="more__details" href="{{ url('store-ads-detials') }}/${v.id}">اعرف أكتر</a>
                        </section>
                    </div>
                </div>
            </div>


`);
        }
	})
    if (s.datas.length > 0){
        $('.new__cards').append(`

  
                        <div class="product__card big__card regular__hover"  style="border:none;box-shadow:none;padding:0;">
                         
                            <div class="product__content">
                              
                                <section class="product__bottom">
                               
                                <span style="cursor: pointer;" data-count="${s.limit}" data-id="${s.id}" class="mores more__details"> المزيد</span>
                                </section>
                            </div>
                        </div>
       
       
        
		`);
    }
    	
	}});
});




$(document).on('keyup','.ser', function(e){
    var data = {
		search : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-store-search-by-name') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
        console.log(s);
       
		$.each(s.datas,function(k,v){
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                                <img alt='product__image'
                                     src="{{asset('uploads/stores/alboum/')}}/${v.store_adsimages.slice(0)[0].image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('store-ads-detials') }}/${v.id}">
                                    ${v.title}
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
                                        <span class="address">${v.address}</span>
                                    </section>
                                    <a class="more__details" href="{{ url('store-ads-detials') }}/${v.id}">اعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>

        
		`);
	})
 
	}});
});





$(document).on('click','.mores', function(){
    var $ele = $(this).parent().parent().parent();
    var data = {
		id : $(this).data('id'),
        count   : $(this).data('count'),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-store-search-more') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
        console.log(s);
        $ele.fadeOut().remove();
		$.each(s.datas,function(k,v){
            
            if(v.store_adsimages.length > 0){
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card big__card regular__hover">
                            <div class="image__card">
                                <img alt='product__image'
                                     src="{{asset('uploads/stores/alboum/')}}/${v.store_adsimages.slice(0)[0].image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('store-ads-detials') }}/${v.id}">
                                    ${v.title}
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
                                        <span class="address">${v.address}</span>
                                    </section>
                                    <a class="more__details" href="{{ url('store-ads-detials') }}/${v.id}">اعرف أكتر</a>
                                </section>
                            </div>
                        </div>
                    </div>

        
		`);}else{
            $('.new__cards').append(`

<div class="col-12">
                <div class="product__card big__card regular__hover">
                    <div class="image__card">
                     
                    </div>
                    <div class="product__content">
                        <header class="product__title">
                            <a href="{{ url('store-ads-detials') }}/${v.id}">
                            ${v.title}
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
                                <span class="address">${v.address}</span>
                            </section>
                            <a class="more__details" href="{{ url('store-ads-detials') }}/${v.id}">اعرف أكتر</a>
                        </section>
                    </div>
                </div>
            </div>


`);
        }
	})
    if (s.datas.length > 0){
        $('.new__cards').append(`

                        <div class="product__card big__card regular__hover"  style="border:none;box-shadow:none;padding:0;">
                         
                            <div class="product__content">
                              
                                <section class="product__bottom">
                               
                                <span style="cursor: pointer;" data-count="${s.limit}" data-id="${s.id}" class="mores more__details"> المزيد</span>
                                </section>
                            </div>
                        </div>
    
        
		`);
        document.documentElement.scrollTop
    }
    
	}});
});

</script>

@endsection