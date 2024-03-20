@extends('layouts.front')
@section('style')
<title>   شركات الدليل </title>
<style type="text/css">


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
   <!-- Start Header -->
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
    <!-- End Partners -->
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">دليل الشركات</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

    <!-- Start Search Box -->
    <article class="inner-search-box d-lg-block d-none">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-6">
                    <i class="fas  fa-search"></i>
                    <h4 class="title">بحث:</h4>
                    <input class="search__input ser" id="searchInput" placeholder="بحث" type="search">
                </section>
                <section class="tabs tabs-6">
                    <i class="fas fa-list"></i>
                    <h4 class="title">القطاع:</h4>
                    <select class="form-control" onChange="window.location.href=this.value">
                        @foreach($secs as $value)
                        <option value="{{ route('front_section',$value->type) }}"{{ isset($parint) && $parint->id == $value->id  ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-6">
                    <i class="fas fa-list"></i>
                    <h4 class="title">القسم:</h4>
                    <select class="select2 sec">
                        @foreach($parint->SubSections as $value)
                        <option value="{{$value->id}}" {{$section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-6">
                <i class="fas fa-map-marker-alt"></i>
                    <h4 class="title">الدول:</h4>
                    <select  class="select2 country">
                        @foreach($countries as $value)
                        <option value="{{$value->id}}"{{ isset($Country) && $Country == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-6">
                <i class="fas fa-map-marker-alt"></i>
                    <h4 class="title">المدينة:</h4>
                    <select  class="select2 city">
                        @foreach($cities as $value)
                        <option value="{{$value->id}}"{{ isset($city) && $city == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs tabs-6">
                    <i class="fas fa-sort-amount-down"></i>
                    <h4 class="title">الترتيب:</h4>
                    <select name="sort" onChange="window.location.href=this.value">
                        <option value="{{ route('front_companies_sort_name_alph',$section->id) }}" {{ isset($sort) && $sort == "2" ? 'selected'  :'' }}> الأبجدي</option>
                        <option value="{{ route('front_companies_sort_rate',$section->id) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}> َالأكثر تقيما</option>
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
                                                <input type="radio" id="tab-1-val-{{$value->id}}" data-name="{{$value->type}}"  class="sec" name="tab-1-values" value="{{$value->id}}"
                                                {{ (isset($parint) && $parint->id == $value->id)  ? 'checked'  :'' }} >
                                                <label for="tab-1-val-{{$value->id}}">{{$value->name}}</label>
                                            </a>
                                        </li>
                                    @endforeach  
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <i class="fas fa-list"></i>
                                        القسم
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo"
                                data-parent="#accordion">
                                <div class="card-body">
                                    <ul class="long__list sub_sections">
                                    @foreach($parint->SubSections as $value)
                                        <li>
                                            <a>
                                                <input type="radio" id="tab-2-val-{{$value->id}}"  class="subs" name="tab-2-values" value="{{$value->id}}"
                                                {{ isset($section) && $section->id == $value->id ? 'checked'  :'' }} >
                                                <label for="tab-2-val-{{$value->id}}">{{$value->name}}</label>
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
                                                <input type="radio" id="tab-5-val-1" class="rate" name="tab-5-values" value=""
                                                {{ isset($sort) && $sort == "1" ? 'checked'  :'' }} >
                                                <label for="tab-5-val-1">الأكثر تقيماَ</label>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                </section>
                <!-- End Right Section -->

                <!-- Start Left Section -->
                <section class="left col-lg-9 col-md-12 col-sm-12">
                    <section class="title-box">
                        <h2 class="title section_name"> {{$section->name}}</h2>
                        <span class="sections-number"><span id="sections-number">{{count($section->Company)}}</span> {{$section->type}}</span>
                    </section>
                    <div class="new__cards row">

                        @foreach($compsort as $value)

                        <div class="col-12">
                            <div class="featured__card__container">
                                <div class="product__card big__card paid__green__card">
                                    <div class="featured__container green__card">
                                        <p class="featured__label">الراعى</p>
                                    </div>
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
                                        <div class="rate-box">
                                            <div class="rating-readonly" data-rate-value="{{$value->rate}}"></div>
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
                                            <a class="more__details" href="{{ route('front_company',$value->id) }}">أعرف أكتر</a>
                                        </section>
                                    </div>
                                
                                </div>
                            </div>
                        </div>

                        @endforeach
                    
                        @foreach($companies as $key => $value)

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
                                    <div class="product__rating com-{{$key}}" title="{{$value->rate}}">
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
                                        <a class="more__details" href="{{ route('front_company',$value->id) }}">أعرف أكتر</a>
                                    </section>
                                </div>
                            
                            </div>
                        </div>
                        @endforeach
                        
                    </div>

                    <div class="row pagination__container">
                        <div class="col-12">
                        {{$companies->links()}}
                        </div>
                    </div>

                   
                <!-- End Left Section -->
                {{csrf_field()}}
                </section>
          
        </div>
    
    
    </article>
    <!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>

{{--<script>--}}
{{--    if (window.performance && window.performance.navigation.type == window.performance.navigation.TYPE_BACK_FORWARD) {--}}
{{--        console.log('yes this page call from back');--}}
{{--        location.reload();--}}

{{--    }--}}
{{--</script>--}}
<input type="hidden" id="com" value="{{json_encode($companies)}}">
<script>

$(document).ready(function(){
    var companies = "{{$companies->total()}}"
    $('#sections-number').text(companies);
    // console.log('companies =>'+companies);
    if (window.performance && window.performance.navigation.type == window.performance.navigation.TYPE_BACK_FORWARD) {
        console.log('pervious');
        function companyRate(){
            var companies = $('#com').val();
            companies = JSON.parse(companies).data;
            $.each(companies,function(key,value) {
                $.ajax({
                    url: "{{ url('guide-rate-company') }}"+'/'+value.id,
                    method: 'get',
                    success: function (response, result) {
                        var rate = Math.round(response);
                        var html = '';
                        for(var i = 0;i<5-rate;i++) {
                            html += `<div class="silver"></div>`;
                        }
                        for(var i = 0;i<rate;i++) {
                            html += `<div class="star"></div>`;
                        }
                        $('.com-'+key).empty();
                        $('.com-'+key).append(html);
                    }
                });
            });
        }
        let timerId = setInterval(companyRate, 100);
        setTimeout(() => { clearInterval(timerId); }, 100);
    }
})
</script>

<script type="text/javascript">

$(document).on('change','.sec', function(){
    var link = "{{url('Guide-companies')}}"+'/' + $(this).val();//$(this).attr('data-name');
            window.location.href = link
});

$(document).on('change','.country', function(){

var link = "{{url('Guide-companies',$section->id)}}" + "?country=" +$(this).val()
        window.location.href = link

});

$(document).on('change','.city', function(){
var link = "{{url('Guide-companies',$section->id)}}" + "?city=" +$(this).val()
        window.location.href = link

});
// get sub sections
$(document).on('change','.sec', function(){

var data = {
section_id    : $(this).val(),
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-sub-section-search') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        //alert('data =>'+JSON.stringify(s));
        $('.sub_sections').html('')
        
        $.each(s,function(k,v){
        $('.sub_sections').append(`
            <li>
                <a>
            
                    <input type="radio" id="tab-1-val-${v.id}"  class="subs" name="tab-1-values" value="${v.id}">
                    <label for="tab-1-val-${v.id}">${v.name}</label>
                </a>
            </li>
        `);
    })
    }});

});

$(document).on('change','.subs', function(){

    var id = $(this).val();
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}
    
	$.ajax({
	url     : "{{ url('get-companies-guide-search') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('');

        $('.rate').val(id);
        $('#sections-number').text(s.length);
		$.each(s,function(k,v){
            var html = '';
            html = `<div class="col-12">
                            <div class="product__card big__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/company/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('Guide-company') }}/${v.id}">
                                        ${v.name}
                                        </a>
                                    </header>
                                    <div class="product__rating">`;
                                    for(var i = 0;i<5-Math.round(v.rate);i++) {
                                        html += `<div class="silver"></div>`;
                                    }
                                    for(var i = 0;i<Math.round(v.rate);i++) {
                                        html += `<div class="star"></div>`;
                                    }
                                    html += `</div>
                                    <section class='product__info'>
                                        <p class="note text-center">
                                        ${v.short_desc}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address"> ${v.address}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('Guide-company') }}/${v.id}">أعرف أكتر</a>
                                    </section>
                                </div>

                            </div>
                        </div>`;

                $('.new__cards').append(html);
            });

            $('.sec > option').each(function(me,mee){
                if(mee.value == id)
                {
                    $(this).attr('selected',true)
                }else{
                    $(this).removeAttr('selected')
                }
                $('select').niceSelect('update');
            });
            $('.section_name').text($('.sec').find(':selected').text());
	    }


    });
});


$(document).on('keyup','.ser', function(e){
    var id = $(this).val();
    var data = {
		search : $(this).val(),
		_token     : $("input[name='_token']").val()
	}
    
	$.ajax({
	url     : "{{ url('get-companies-guide-search-by-name') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('');
		$.each(s,function(k,v){
            var html = '';
            html = `<div class="col-12">
                            <div class="product__card big__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/company/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('Guide-company') }}/${v.id}">
                                        ${v.name}
                                        </a>
                                    </header>
                                    <div class="product__rating">`;
            for(var i = 0;i<5-Math.round(v.rate);i++) {
                html += `<div class="silver"></div>`;
            }
            for(var i = 0;i<Math.round(v.rate);i++) {
                html += `<div class="star"></div>`;
            }
            html += `</div>
                                    <section class='product__info'>
                                        <p class="note text-center">
                                        ${v.short_desc}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address"> ${v.address}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('Guide-company') }}/${v.id}">أعرف أكتر</a>
                                    </section>
                                </div>

                            </div>
                        </div>`;

            $('.new__cards').append(html);
            })
	    }
    });
});



$(document).on('change','.rate', function(){
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-companies-guide-search-rate') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
		$.each(s,function(k,v){
            var html = '';
            html = `<div class="col-12">
                            <div class="product__card big__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/company/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('Guide-company') }}/${v.id}">
                                        ${v.name}
                                        </a>
                                    </header>
                                    <div class="product__rating">`;
            for(var i = 0;i<5-Math.round(v.rate);i++) {
                html += `<div class="silver"></div>`;
            }
            for(var i = 0;i<Math.round(v.rate);i++) {
                html += `<div class="star"></div>`;
            }
            html += `</div>
                                    <section class='product__info'>
                                        <p class="note text-center">
                                        ${v.short_desc}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="address-box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="address"> ${v.address}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('Guide-company') }}/${v.id}">أعرف أكتر</a>
                                    </section>
                                </div>

                            </div>
                        </div>`;

            $('.new__cards').append(html);
	})
	}});
});

</script>




@endsection