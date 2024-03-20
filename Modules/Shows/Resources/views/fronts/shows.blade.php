
@extends('layouts.front')
@section('style')
<title>  المعارض</title>
<style type="text/css">



</style>
@endsection
@section('content')

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
  <div class="breadcrumb__container container ">
    <div class="custom__breadcrumb">
        <h1 class="page__title">المعارض</h1>
    </div>
  </div>
    <!-- End breadcrumb -->
<!-- Start Search Box -->
<article class="inner-search-box d-lg-block d-none">
    <div class="container">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
            <section class="tabs tabs-5">
                <i class="fas  fa-search"></i>
                <h4 class="title">بحث:</h4>
                <input class="search__input ser" id="searchInput" placeholder="بحث" type="search">
            </section>
            <section class="tabs  tabs-5">
                <i class="fas fa-list"></i>
                <h4 class="title">القطاع:</h4>
                <select class="form-control slecs" onChange="window.location.href=this.value">
                    @foreach($secs as $value)
                    <option class="op{{$value->id}}" data="{{$value->id}}" value="{{ route('front_shows',$value->id) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs  tabs-5">
                <i class="fas fa-map-marker-alt"></i>
                    <h4 class="title">الدول:</h4>
                    <select  class="select2" onChange="window.location.href=this.value">
                        @foreach($countries as $value)
                        <option value="{{ route('front_show_country',$value->id) }}"{{ isset($Country) && $Country == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
            <section class="tabs tabs-5">
                <i class="fas fa-map-marker-alt"></i>
                <h4 class="title">المدينة:</h4>
                <select  class="select2" onChange="window.location.href=this.value">
                    @foreach($cities as $value)
                    <option value="{{ route('front_show_city',$value->id) }}"{{ isset($city) && $city == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach
                </select>
            </section>
            <section class="tabs tabs-5">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title sect">الترتيب:</h4>
                <select name="sort" onChange="window.location.href=this.value">
                    <option value="{{ route('front_shows_view',$section->type) }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الأبجدي</option>
                    <option value="{{ route('front_shows_view',$section->type) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}>الاكثر تداولاً</option>
                    <option value="{{ route('front_shows_last',$section->type) }}"{{ isset($sort) && $sort == "2" ? 'selected'  :'' }}>  الأحدث</option>
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
            <div class="right col-lg-3 col-md-12 col-sm-12">
                <div class="sidebar__sticky">
                    <h2 class="main-title" id="show-hide-accordion">
                        حدد بحثك
                        <i class="icon-s fas fa-search"></i>
                    </h2>
                    <div id="accordion" class="accordion">
                        <div class="card">
                            <div class="card-header" id="headingSearch">
                                <h5 class="mb-0">
                                    <button aria-controls="collapseSearch" aria-expanded="false" class="btn btn-link collapsed" data-target="#collapseSearch" data-toggle="collapse">
                                        <i class="fas fa-search"></i>
                                        البحث
                                    </button>
                                </h5>
                            </div>
                            <div aria-labelledby="headingSearch" class="collapse show" data-parent="#accordion"
                                 id="collapseSearch">
                                <div class="card-body">
                                    <input class="search__input ser" placeholder="بحث" type="search">
                                </div>
                            </div>
                        </div>
                        <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-align-justify"></i>القطاع
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="">
                            <div class="card-body">
                                <ul>
                                @foreach($secs as $value)
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-1-val-{{$value->id}}"  class="sec" name="tab-1-values" value="{{$value->id}}"
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
                </div>
            </div>
            <!-- End Right Section -->

            <!-- Start Left Section -->
            <section class="left col-lg-9 col-md-12 col-sm-12">
                <div class="new__cards row">
                                        
                    @foreach($shows as $value)

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
                                            @if(count($value->time()) > 0)
                                               {{$value->time()[0]}} 
                                            @endif
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
                                            <span class="content">{{$value->Country->name}} -  {{$value->City->name}}</span>
                                        </section>
                                        <a class="more__details" href="{{ route('front_one_show',$value->id) }}">مهتم</a>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    @endforeach
                    
                </div>
                <div class="row pagination__container">
                    <div class="col-12">
                    {{$shows->links()}}
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
<script type="text/javascript">


$(document).on('keyup','.ser', function(e){
    var data = {
		search : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-show-search-by-name') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
        console.log(s);
     
		$.each(s.datas,function(k,v){
            
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="featured__card__container">
                            <div class="product__card super__ultra__card regular__hover">
                               
                                <div class="image__card">
                                    <img alt='product__image'
                                         src="{{asset('uploads/show/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('one-show') }}/${v.id}">
                                        ${v.name}
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
                                        ${v.desc.substring(0,50)}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-users"></i>
                                            <span class="content">5000 متابع</span>
                                        </section>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="content">${v.country.name} -   ${v.city.name}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('one-show') }}/${v.id}">مهتم</a>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

      
        
		`);
	})
   
	}});
});



$(document).on('change','.sec', function(){
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-show-search') }}",
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
            
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="featured__card__container">
                            <div class="product__card super__ultra__card regular__hover">
                               
                                <div class="image__card">
                                    <img alt='product__image'
                                         src="{{asset('uploads/show/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('one-show') }}/${v.id}">
                                        ${v.name}
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
                                        ${v.desc.substring(0,50)}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-users"></i>
                                            <span class="content">5000 متابع</span>
                                        </section>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="content">${v.country.name} -   ${v.city.name}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('one-show') }}/${v.id}">مهتم</a>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

      
        
		`);
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



$(document).on('click','.mores', function(){
    var $ele = $(this).parent().parent().parent();
    var data = {
		id : $(this).data('id'),
        count   : $(this).data('count'),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-show-search-more') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
        console.log(s);
        $ele.fadeOut().remove();
		$.each(s.datas,function(k,v){
            
		$('.new__cards').append(`
        <div class="col-12">
                        <div class="featured__card__container">
                            <div class="product__card super__ultra__card regular__hover">
                               
                                <div class="image__card">
                                    <img alt='product__image'
                                         src="{{asset('uploads/show/images/')}}/${v.image}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ url('one-show') }}/${v.id}">
                                        ${v.name}
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
                                        ${v.desc.substring(0,50)}
                                        </p>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-users"></i>
                                            <span class="content">5000 متابع</span>
                                        </section>
                                    </section>
                                    <section class="product__bottom">
                                        <section class="content__box">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span class="content">${v.country.name} -   ${v.city.name}</span>
                                        </section>
                                        <a class="more__details" href="{{ url('one-show') }}/${v.id}">مهتم</a>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
        
		`);
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