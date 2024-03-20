
@extends('layouts.front')
@section('style')
<title>     الدليل قسم  {{$section->name}} </title>
@endsection
@section('content')

{{csrf_field()}}

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
    <!-- End Header -->
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
            <div class="edge"></div>
            <section class="tabs tabs-3">
                <i class="fas  fa-search"></i>
                <h4 class="title">بحث:</h4>
                <input class="search__input ser" id="searchInput" placeholder="بحث" type="search">
            </section>
            <section class="tabs  tabs-3">
                <i class="fas fa-list"></i>
                <h4 class="title">القطاع:</h4>
                <select class="form-control slecs" onChange="window.location.href=this.value">
                    @foreach($secs as $value)
                    <option class="op{{$value->id}}" data="{{$value->id}}" value="{{ route('front_section',$value->type) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs tabs-3">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title sect">الترتيب:</h4>
                <select name="sort" onChange="window.location.href=this.value">
                    <option value="{{ route('front_section_sort_name',$section->type) }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الأبجدي</option>
                    <option value="{{ route('front_section_sort',$section->type) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}>َالأكثر تداولا</option>
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
        <section class="right col-sm-12 col-md-12 col-lg-3   d-none d-lg-block d-xl-block position-relative">
            <section style="position: sticky;top:1rem;right: 0;">
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
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                    aria-expanded="true" aria-controls="collapseOne">
                                    <i class="fas fa-align-justify"></i>
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
                                            <input type="radio" id="tab-5-val-0"  class="sort" name="tab-5-values" value="0"{{ isset($sort) && $sort == "0" ? 'checked'  :'' }}>
                                            <label for="tab-5-val-0">الأبجدي</label>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-5-val-1"  class="sort" name="tab-5-values" value="1"{{ isset($sort) && $sort == "1" ? 'checked'  :'' }}>
                                            <label for="tab-5-val-1">الأكثر تداولا</label>
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
            <section class="left col-lg-9 col-md-7 col-sm-12">
                <section class="title-box">
                    <h2 class="title sects">{{$section->name}}</h2>
                    <span class="sections-number">{{count($sections)}} قسم</span>
                </section>
                <div class="new__cards row">
                    @foreach($sections as $value)

                    <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img class="lazy" alt='product__image'
                                     data-src="{{asset('uploads/sections/avatar/'.$value->image)}}"/>
                            </div>
                            <div class="product__content" style="min-width : auto">
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
                            <div class="logo__main__card one-full-slider">
                                @foreach($value->logooos() as $logo)
                                    <img class="company__logo lazy" data-src="{{asset('uploads/full_images/'.$logo->image)}}" alt="company logo">
                                @endforeach
                            </div>
                        </div>
                    </div>
                       
                    @endforeach
                </div>
            </section>
            <!-- End Left Section -->

        </div>
   
</article>
<!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('public/Front_End/jquery_lazy/jquery.lazy.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>

<script type="text/javascript">

$(document).on('change','.sec', function(e){
    var data = {
		section_id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-search-in-guide') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){


		$('.new__cards').html('')

        $('.sections-number').html(s.datas.length + 'قسم')
            $('.sects').html(s.seco.name)

            $('.slecs option').each(function(){

            if($(this).attr('data') != data.section_id){
                $(this).removeAttr('selected');
            }else{
                $(this).attr('selected', true);
                $('select').niceSelect('update');
            }
        });

		$.each(s.datas,function(k,v){
            $('.sects').html(s.seco.name)

            
        
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img class='lazy' alt='product__image'
                                     data-src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content"  style="min-width : auto">
                                <header class="product__title">
                                    <a href="{{ url('Guide-companies') }}/${v.id}">
                                    ${v.name}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note">
                                    ${v.company_count} ${v.type}
                                    </p>
                                </section>
                            </div>
                            <div class="logo__main__card one-full-slider loooo${v.id}">
                        
                            </div>
                        </div>
                    </div>

		`);
        $.each(s.ads,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.loooo'+v.id).append(`<img class="company__logo lazy" data-src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">`);
            }
           
        });
            $("img.lazy").lazy({
                effect: "fadeIn"
            });
    });

    
$('.one-full-slider').not('.slick-initialized').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    slidesToShow: 1,
    adaptiveHeight: true,
    pauseOnHover: true,
    pauseOnFocus: true,
    draggable: true,
    arrows: false,
    rtl: true,
})
	}});

});


///sort

$(document).on('change','.sort', function(e){
    var data = {
		section_id : $('.sec:checked').val(),
        sort : $(this).val(),
		_token     : $("input[name='_token']").val()
	}
	$.ajax({
	url     : "{{ url('get-section-sort-in-guide') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){

		$('.new__cards').html('')

        $('.sections-number').html(s.datas.length + 'قسم')
            $('.sects').html(s.seco.name)

            $('.slecs option').each(function(){
  
            if($(this).attr('data') != s.seco.id){
                $(this).removeAttr('selected');
            }else{
                $(this).attr('selected', true);

                $('select').niceSelect('update');
            }
        })


		$.each(s.datas,function(k,v){
            $('.sects').html(s.seco.name)

		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img class='lazy' alt='product__image'
                                     data-src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content" style="min-width : auto">
                                <header class="product__title">
                                    <a href="{{ url('Guide-companies') }}/${v.id}">
                                    ${v.name}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note">
                                    ${v.company_count} ${v.type}
                                    </p>
                                </section>
                            </div>
                            <div class="logo__main__card one-full-slider loooo${v.id}">
                        
                            </div>
                        </div>
                    </div>

		`);
        $.each(s.ads,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.loooo'+v.id).append(`<img class="company__logo lazy" data-src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">`);
            }
           
        });
            $("img.lazy").lazy({
                effect: "fadeIn"
            });
    });

    
$('.one-full-slider').not('.slick-initialized').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    slidesToShow: 1,
    adaptiveHeight: true,
    pauseOnHover: true,
    pauseOnFocus: true,
    draggable: true,
    arrows: false,
    rtl: true,
})
	}});
    
});
///search

$(document).on('keyup','.ser', function(e){
    var data = {
        search : $(this).val(),
        section_id : {{$section->id}},
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-search-name-in-guide') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
        $('.sections-number').html(s.datas.length + 'قسم')
		$.each(s.datas,function(k,v){
    

		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img class='lazy' alt='product__image'
                                     data-src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content" style="min-width : auto">
                                <header class="product__title">
                                    <a href="{{ url('Guide-companies') }}/${v.id}">
                                    ${v.name}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note">
                                    ${v.company_count} ${v.type}
                                    </p>
                                </section>
                            </div>
                            <div class="logo__main__card one-full-slider loooo${v.id}">
                        
                            </div>
                        </div>
                    </div>

		`);
        $.each(s.ads,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.loooo'+v.id).append(`<img class="company__logo lazy" data-src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">`);
            }
           
        });
            $("img.lazy").lazy({
                effect: "fadeIn"
            });
    });

    
$('.one-full-slider').not('.slick-initialized').slick({
    dots: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 3000,
    speed: 1000,
    slidesToShow: 1,
    adaptiveHeight: true,
    pauseOnHover: true,
    pauseOnFocus: true,
    draggable: true,
    arrows: false,
    rtl: true,
})
	}});
    
});
</script>

<script>
    $(function() {
        $('.lazy').Lazy();
    });
</script>
@endsection