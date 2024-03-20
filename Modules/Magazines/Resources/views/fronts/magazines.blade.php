
@extends('layouts.front')
@section('style')
<title>    الدلائل والمجلات</title>
<style type="text/css">

.ajax-load{

background: #fff;
padding: 10px 0px;
width: 100%;
text-align:center;

}
.product__card .silver {
    display: inline-block;
    width: 24px;
    height: 24px;
    border: 1px solid lightslategray;
    background: linear-gradient(to right, lightslategray 100%, lightslategray);
    -webkit-clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%); }

</style>
@endsection
@section('content')
<!-- Start Header -->
<header class="banner__header">      
    <div class="container-fluid one-full-slider-banner">
        @foreach($ads as $ad)
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
    <!-- End Partners -->
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
        <h1 class="page__title">الدلائل والمجلات</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

    <!-- Start Search Box -->
    <article class="inner-search-box d-lg-block d-none">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-4">
                    <i class="fas  fa-search"></i>
                    <h4 class="title">بحث:</h4>
                    <input class="search__input ser" id="searchInput" placeholder="بحث" type="search">
                </section>
                <section class="tabs  tabs-4">
                    <i class="fas fa-list"></i>
                    <h4 class="title">القطاع:</h4>
                    <select class="form-control slecs" onChange="window.location.href=this.value">
                        @foreach($secs as $value)
                        <option data="{{$value->id}}"  value="{{ route('front_magazines',$value->id) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-4">
                    <i class="fas fa-list"></i>
                    <h4 class="title">المدينة:</h4>
                    <select  class="form-control city">
                        <option value=" "{{ isset($city) && $city == $value->id ? 'selected'  :'' }}> الكل </option>
                        @foreach($cities as $value)
                            <option value="{{$value->id}}"{{ isset($city) && $city == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-4">
                    <i class="fas fa-sort-amount-down"></i>
                    <h4 class="title">الترتيب:</h4>
                    <select name="sort" onChange="window.location.href=this.value">
                        <option value="{{ route('front_magazines',$section->id) }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الأبجدي</option>
                        <option value="{{ route('front_magazines_sort_rate_magazines',$section->id) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}>الأكثر تقيماً</option>
                    </select>
                </section>
            </form>
        </div>
    </article>
    <!-- End Search Box -->
  
    <!-- Start Global Content -->
    <article class="global__container container">
        <div class="row">

           
            <!-- Start Right Section -->
            <section class="right col-lg-3 col-md-12 col-sm-12 position-relative">
                <section class="sidebar__sticky">
                    <h2 class="main-title no__top__radius" id="show-hide-accordion">
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
                            <div aria-labelledby="headingSearch" class="collapse" data-parent="#accordion"
                                 id="collapseSearch">
                                <div class="card-body">
                                    <input class="search__input ser" placeholder="بحث" type="search">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                        aria-expanded="true" aria-controls="collapseOne">
                                        <i class="fas fa-align-justify"></i>
                                    القطاع
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                            data-parent="">
                            <div class="card-body">
                                <ul>
                                @foreach($secs as $value)
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-1-val-{{$value->id}}"  class="sec sectionsp" name="tab-1-values" value="{{$value->id}}"
                                            {{ isset($section) && $section->id == $value->id ? 'checked'  :'' }}>
                                            <label for="tab-1-val-{{$value->id}}">{{$value->name}}</label>
                                        </a>
                                    </li>
                                @endforeach  
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse"
                                    data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    <i class="fas fa-sort-amount-down"></i>
                                    الترتيب
                                </button>
                            </h5>
                        </div>
                        <div id="collapseFive" class="collapse show" aria-labelledby="headingFive"
                            data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-5-val-0"  class="rate" name="tab-5-values" value="0"{{ isset($sort) && $sort == "0" ? 'checked'  :'' }}>
                                            <label for="tab-5-val-0">الأبجدي</label>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-5-val-1" class="rate" name="tab-5-values" value="1"
                                            {{ isset($sort) && $sort == "1" ? 'checked'  :'' }} >
                                            <label for="tab-5-val-1">الأكثر تقيماَ</label>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>
            </section>
            <!-- End Right Section -->

            <!-- Start Left Section -->
            <section class="left col-lg-9 col-md-7 col-sm-12">
                <section class="title-box">
                    <h2 class="title sects"> {{$section->name}}</h2>
                    <span class="sections-number">{{count($magazines)}} مجلة</span>
                </section>
                <div class="new__cards row">
                                        
                    @foreach($magazines as $key => $value)
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
                                    <div class="product__rating magazin-{{$key}}">
                                            @for($i=0;$i<5-round($value->rate);$i++)
                                                <div class='silver'></div>
                                            @endfor
                                            @for($i=0;$i<round($value->rate);$i++)
                                                <div class='star'></div>
                                            @endfor
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
                                        <a class="more__details" href="{{ route('front_magazine',$value->id) }}">أعرف أكتر</a>
                                    </section>
                                </div>
                            
                            </div>
                        </div>
                    @endforeach
                    
                </div>
                <div class="row pagination__container">
                        <div class="col-12">
                        {{$magazines->links()}}
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
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>
<input type="hidden" id="magazines" value="{{json_encode($magazines)}}">
<script>

    $(document).ready(function(){
        if (window.performance && window.performance.navigation.type == window.performance.navigation.TYPE_BACK_FORWARD) {
            function magazinRate(){
                var magazines = $('#magazines').val();
                magazines = JSON.parse(magazines).data;
                $.each(magazines,function(key,value) {
                    $.ajax({
                        url: "{{ url('magazines-rate-magazin') }}"+'/'+value.id,
                        method: 'get',
                        success: function (response, result) {
                            var rate = Math.round(response);
                            console.log('rate => '+rate);
                            var html = '';
                            for(var i = 0;i<5-rate;i++) {
                                html += `<div class="silver"></div>`;
                            }
                            for(var i = 0;i<rate;i++) {
                                html += `<div class="star"></div>`;
                            }
                            $('.magazin-'+key).empty();
                            $('.magazin-'+key).append(html);
                        }
                    });
                });
            }
            let timerId = setInterval(magazinRate, 100);
            setTimeout(() => { clearInterval(timerId); }, 100);
        }
    })
</script>


<script type="text/javascript">
$(document).on('change','.city', function(){
var link = "{{url('magazines-magazines',$section->id)}}" + "?city=" +$(this).val()
        window.location.href = link

});

$(document).on('keyup','.ser', function(e){
    var id = $(this).val();
    var data = {
		search : $(this).val(),
		_token     : $("input[name='_token']").val(),
        section_id: {{$section->id}}
	}

	$.ajax({
	url     : "{{ url('get-magazines-search-name') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('');
        $('.sections-number').html(s.datas.length + 'مجلة')
        console.log(s);

		$.each(s.datas,function(k,v){
            var html = '';
            html = '<div class="col-12">'+
                '<div class="product__card big__card regular__hover">'+
                '<div class="image__card">'+
                '<img alt="product__image"src="{{asset('uploads/magazine/images/')}}/'+v.image+'"/>'+
                '</div>'+
                '<div class="product__content">'+
                '<header class="product__title">'+
                '<a href="{{ url('magazines-magazine') }}/'+v.id+'">'+v.name +'</a>'+
                '</header>'+
                '<div class="product__rating">';
            for(var i = 0;i<5-Math.round(v.rate);i++) {
                html += '<div class="silver"></div>';
            }
            for(var i = 0;i<Math.round(v.rate);i++) {
                html += '<div class="star"></div>';
            }
            html += '</div>';

            html += '<section class="product__info">'+
                '<p class="note text-center">'+ v.short_desc+ '</p>'+
                '</section>'+
                '<section class="product__bottom">'+
                '<section class="address-box">'+
                '<i class="fas fa-map-marker-alt"></i>'+
                '<span class="address">'+ v.address+'</span>'+
                '</section>'+
                '<a class="more__details" href="{{ url('magazines-magazine') }}/'+v.id+'>أعرف أكتر</a>'+
                '</section>'+
                '</div>'+
                '</div>'+
                '</div>';
            $('.new__cards').append(html);
{{--            $('.new__cards').append(`--}}

{{--<div class="col-12">--}}
{{--            <div class="product__card big__card regular__hover">--}}
{{--                <div class="image__card">--}}
{{--                    <img alt='product__image'--}}
{{--                        src="{{asset('uploads/magazine/images/')}}/${v.image}"/>--}}
{{--                </div>--}}
{{--                <div class="product__content">--}}
{{--                    <header class="product__title">--}}
{{--                        <a href="{{ url('magazines-magazine') }}/${v.id}">--}}
{{--                        ${v.name}--}}
{{--                        </a>--}}
{{--                    </header>--}}
{{--                    <div class="product__rating">--}}
{{--                                        <div class='star'></div>--}}
{{--                                        <div class='star'></div>--}}
{{--                                        <div class='star'></div>--}}
{{--                                        <div class='star'></div>--}}
{{--                                    </div>--}}
{{--                    <section class='product__info'>--}}
{{--                        <p class="note text-center">--}}
{{--                        ${v.short_desc}--}}
{{--                        </p>--}}
{{--                    </section>--}}
{{--                    <section class="product__bottom">--}}
{{--                        <section class="address-box">--}}
{{--                            <i class="fas fa-map-marker-alt"></i>--}}
{{--                            <span class="address"> ${v.address}</span>--}}
{{--                        </section>--}}
{{--                        <a class="more__details" href="{{ url('magazines-magazine') }}/${v.id}">أعرف أكتر</a>--}}
{{--                    </section>--}}
{{--                </div>--}}
{{--            --}}
{{--            </div>--}}
{{--        </div>--}}


{{--        `);--}}
            })
	    }
    });
});


$(document).on('change','.sectionsp', function(){
    var id = $(this).val();
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}
    
	$.ajax({
	url     : "{{ url('get-magazines-guide-search') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('');

        console.log(s);
        $('.sections-number').html(s.datas.length + 'مجلة')
            $('.sects').html(s.seco.name)
        $('.rate').val(id);
        $('.slecs option').each(function(){
            
            if($(this).attr('data') != s.seco.id){
                $(this).removeAttr('selected');
            }else{
                $(this).attr('selected', true);

                $('select').niceSelect('update');


            }


            })
		$.each(s.datas,function(k,v){
            var html = '';
            html = '<div class="col-12">'+
                '<div class="product__card big__card regular__hover">'+
                '<div class="image__card">'+
                '<img alt="product__image"src="{{asset('uploads/magazine/images/')}}/'+v.image+'"/>'+
                '</div>'+
                '<div class="product__content">'+
                '<header class="product__title">'+
                '<a href="{{ url('magazines-magazine') }}/'+v.id+'">'+v.name +'</a>'+
                '</header>'+
                '<div class="product__rating">';
            for(var i = 0;i<5-Math.round(v.rate);i++) {
                html += '<div class="silver"></div>';
            }
            for(var i = 0;i<Math.round(v.rate);i++) {
                html += '<div class="star"></div>';
            }
            html += '</div>';

            html += '<section class="product__info">'+
                '<p class="note text-center">'+ v.short_desc+ '</p>'+
                '</section>'+
                '<section class="product__bottom">'+
                '<section class="address-box">'+
                '<i class="fas fa-map-marker-alt"></i>'+
                '<span class="address">'+ v.address+'</span>'+
                '</section>'+
                '<a class="more__details" href="{{ url('magazines-magazine') }}/'+v.id+'>أعرف أكتر</a>'+
                '</section>'+
                '</div>'+
                '</div>'+
                '</div>';
            $('.new__cards').append(html);
        {{--    $('.new__cards').append(`--}}
        {{--    <div class="col-12">--}}
        {{--    <div class="product__card big__card regular__hover">--}}
        {{--        <div class="image__card">--}}
        {{--            <img alt='product__image'--}}
        {{--                src="{{asset('uploads/magazine/images/')}}/${v.image}"/>--}}
        {{--        </div>--}}
        {{--        <div class="product__content">--}}
        {{--            <header class="product__title">--}}
        {{--                <a href="{{ url('magazines-magazine') }}/${v.id}">--}}
        {{--                ${v.name}--}}
        {{--                </a>--}}
        {{--            </header>--}}
        {{--            <div class="product__rating">--}}
        {{--                                <div class='star'></div>--}}
        {{--                                <div class='star'></div>--}}
        {{--                                <div class='star'></div>--}}
        {{--                                <div class='star'></div>--}}
        {{--                            </div>--}}
        {{--            <section class='product__info'>--}}
        {{--                <p class="note text-center">--}}
        {{--                ${v.short_desc}--}}
        {{--                </p>--}}
        {{--            </section>--}}
        {{--            <section class="product__bottom">--}}
        {{--                <section class="address-box">--}}
        {{--                    <i class="fas fa-map-marker-alt"></i>--}}
        {{--                    <span class="address"> ${v.address}</span>--}}
        {{--                </section>--}}
        {{--                <a class="more__details" href="{{ url('magazines-magazine') }}/${v.id}">أعرف أكتر</a>--}}
        {{--            </section>--}}
        {{--        </div>--}}
        {{--    --}}
        {{--    </div>--}}
        {{--</div>--}}


        {{--`);--}}
            })
	    }
    });
});




$(document).on('change','.rate', function(){
    var data = {
		id : $(this).val(),
        sort : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-magazines-guide-search-rate') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('');
        $('.sects').html('');
        $('.sections-number').html(s.datas.length + 'مجلة');
        console.log(s);
		$.each(s.datas,function(k,v){
            var html = '';
            html = '<div class="col-12">'+
                '<div class="product__card big__card regular__hover">'+
                '<div class="image__card">'+
                '<img alt="product__image"src="{{asset('uploads/magazine/images/')}}/'+v.image+'"/>'+
                '</div>'+
                '<div class="product__content">'+
                '<header class="product__title">'+
                '<a href="{{ url('magazines-magazine') }}/'+v.id+'">'+v.name +'</a>'+
                '</header>'+
                '<div class="product__rating">';
                for(var i = 0;i<5-Math.round(v.rate);i++) {
                    html += '<div class="silver"></div>';
                }
                for(var i = 0;i<Math.round(v.rate);i++) {
                    html += '<div class="star"></div>';
                }
            html += '</div>';

            html += '<section class="product__info">'+
                        '<p class="note text-center">'+ v.short_desc+ '</p>'+
                    '</section>'+
                '<section class="product__bottom">'+
                    '<section class="address-box">'+
                        '<i class="fas fa-map-marker-alt"></i>'+
                        '<span class="address">'+ v.address+'</span>'+
                    '</section>'+
                    '<a class="more__details" href="{{ url('magazines-magazine') }}/'+v.id+'>أعرف أكتر</a>'+
                '</section>'+
                    '</div>'+
                    '</div>'+
                    '</div>';
            $('.new__cards').append(html);
	        });
	    }
    });
});

</script>




@endsection