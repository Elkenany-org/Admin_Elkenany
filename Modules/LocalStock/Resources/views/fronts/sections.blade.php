
@extends('layouts.front')
@section('style')


<title>     البورصة اليومية </title>
    <style type="text/css">

        /* ============ desktop view ============ */
        @media all and (min-width: 992px) {

            .dropdown-menu li {
                position: relative;
            }

            .dropdown-menu .submenu {
                display: none;
                position: absolute;
                left: 100%;
                top: -7px;
            }

            .dropdown-menu .submenu-left {
                right: 100%;
                left: auto;
            }

            .dropdown-menu > li:hover {
                background-color: #f1f1f1
            }

            .dropdown-menu > li:hover > .submenu {
                display: block;
            }
        }

        /* ============ desktop view .end// ============ */

        /* ============ small devices ============ */
        @media (max-width: 991px) {

            .dropdown-menu .dropdown-menu {
                margin-left: 0.7rem;
                margin-right: 0.7rem;
                margin-bottom: .5rem;
            }

        }

        /* ============ small devices .end// ============ */

    </style>
@endsection
@section('content')
{{csrf_field()}}
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
                    @if($logo->type == "logo")
                        <div class="item">
                            <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{$logo->image_url}}"></a>
                        <div class="wall"></div>
                        </div>
                    @endif
            @endforeach 
             
            </section>
        </div>
    </article>
    <!-- End Header -->
  <!-- Start breadcrumb -->
    <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">البورصة اليومية</h1>
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
                                <input class="search__input" name="keyword" id="searchInput" value="{{isset($_GET['keyword']) ? $_GET['keyword'] : ''}}" placeholder="بحث" type="search">
                                <button class="btn" formaction="" formmethod="GET" type="submit" style="background-color: #1a5302;color: #fff;border-radius: 6px 0px 0px 6px;position: absolute;left: 10px;padding-bottom: 8px"><i class="fas  fa-search text-light"></i></button>
                    </section>

                <section class="tabs tabs-4">
                <i class="fas fa-puzzle-piece"></i>
                    <h4 class="title">القطاع:</h4>
                    <select class="form-control slecs" onChange="window.location.href=this.value">
                        @foreach($secs as $value)
                        <option class="op{{$value->id}}"  data="{{$value->id}}" value="{{ route('front_local_sections',$value->type) }}"
                        @if(!is_null($section))
                            {{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}
                        @endif
                        >{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs tabs-4">
                    <i class="fas fa-sort-amount-down"></i>
                    <h4 class="title">الترتيب:</h4>
                    <select name="sort" class="sort">
                        <option value="0" {{ isset($_GET['sort']) && $_GET['sort'] == "0" ? 'selected'  :'' }}> الأبجدي</option>
                        <option value="1" {{ isset($_GET['sort']) && $_GET['sort'] == "1" ? 'selected'  :'' }}>الأكثر تداولاَ</option>
                    </select>
                </section>
                <section class="tabs tabs-4">
                    <i class="fas fa-poll"></i>
                    <h4 class="title">إحصائيات:</h4>
                    <a href="  @if(!is_null($section)){{ route('front_local_statistic',$section->id) }}  @endif" class="stats__btn">إحصائيات</a>
                </section>
            </form>
        </div>
    </article>
    <!-- End Search Box -->
    <!-- Start Global Content -->
    <article class="container global__container">
        <div class="row">

                 <!-- Start Right Section -->
                 <section class="right main__right__container col-lg-4 col-md-12 col-sm-12">
                <section class="sidebar__sticky">
                    <h2 class="main-title" id="show-hide-accordion">
                        حدد بحثك
                        <i class="icon-s fas fa-search"></i>
                    </h2>
                    <div class="accordion" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingSearch">
                                <h5 class="mb-0">
                                    <button aria-controls="collapseSearch" aria-expanded="false" class="btn btn-link collapsed" data-target="#collapseSearch" data-toggle="collapse">
                                        <i class="fas fa-search"></i>
                                        البحث
                                    </button>
                                </h5>
                            </div>
                            <div aria-labelledby="headingSearch" class="collapse show" data-parent="#accordion" id="collapseSearch">
                                <form action="" method="GET">
                                    <div class="card-body">
                                        <input class="search__input" name="keyword" value="{{isset($_GET['keyword']) ? $_GET['keyword'] : ''}}" placeholder="بحث" type="search">
{{--                                        ser--}}
                                        <button class="btn" type="submit" style="background-color: #1a5302;color: #fff;border-radius: 6px 0px 0px 6px">بحث</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button aria-controls="collapseOne" aria-expanded="true" class="btn btn-link"
                                            data-target="#collapseOne" data-toggle="collapse">
                                        <i class="fas fa-puzzle-piece"></i>
                                        القطاع
                                    </button>
                                </h5>
                            </div>
                            <div aria-labelledby="headingOne" class="collapse show" data-parent="#accordion"
                                 id="collapseOne">
                                <div class="card-body">
                                <ul>
                                @foreach($secs as $value)
                                    <li>
                                        <a>
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
                        <div id="collapseFive" class="collapse show" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                            <ul>
                                    <li>
                                        <a>
{{--                                            class="sort" name="tab-5-values" --}}
                                            <input type="radio" id="tab-5-val-0"  class="sort" name="sort" value="0"{{ isset($_GET['sort']) && $_GET['sort'] == "0" ? 'checked'  :'' }}>
                                            <label for="tab-5-val-0">الأبجدي</label>
                                        </a>
                                    </li>
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-5-val-1" class="sort" name="sort" value="1"{{ isset($_GET['sort']) && $_GET['sort'] == "1" ? 'checked'  :'' }}>
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
                <section class="main__left__container col-lg-8 col-md-12 col-sm-12">
                    <div class="new__cards row">
                    @if(count($subs) > 0)
                            @foreach($subs as $value)
                            <div class="col-12">
                                <div class="product__card"> 
                                    <div class="image__card">
                                        <img alt='product__image'
                                            src="{{asset('uploads/sections/avatar/'.$value->image)}}"/>
                                    </div>
                                    <div class="product__content"  style="min-width : auto">
                                        <header class="product__title">
                                            <a href="{{ route('front_fodder_stocks',$value->id) }}">  {{$value->name}}</a></header>
                                    </div>
                                    <div class="logo__main__card one-full-slider">
                                        @foreach($value->logooss() as $logo)
                                            <img class="company__logo" src="{{asset('uploads/full_images/'.$logo->image)}}" alt="company logo">
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        @endif
                        @if(count($sections) > 0)
                            @foreach($sections as $value)
                            <div class="col-12">
                                <div class="product__card">
                                    <div class="image__card">
                                        <img alt='product__image' src="{{$value->image_url}}"/>
                                    </div>
                                    <div class="product__content" style="min-width : auto">
                                        <header class="product__title">
                                            <a href="{{ route('front_local_members',$value->id).'/'.$section->type }}">  {{$value->name}}</a></header>
                                    </div>
                                    <div class="logo__main__card one-full-slider">
                                        @foreach($value->logooos() as $logo)
                                            <img class="company__logo" src="{{$logo->image_url}}" alt="company logo">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif
{{--                        @if(count($section->LocalStockSub) > 0)--}}
{{--                            @foreach($section->LocalStockSub as $value)--}}
{{--                                <div class="col-12">--}}
{{--                                    <div class="product__card">--}}
{{--                                        <div class="image__card">--}}
{{--                                            <img alt='product__image' src="{{$value->image_url}}"/>--}}
{{--                                        </div>--}}
{{--                                        <div class="product__content" style="min-width : auto">--}}
{{--                                            <header class="product__title">--}}
{{--                                                <a href="{{ route('front_local_members',$value->id) }}">  {{$value->name}}</a></header>--}}
{{--                                        </div>--}}
{{--                                        <div class="logo__main__card one-full-slider">--}}
{{--                                            @foreach($value->logooos() as $logo)--}}
{{--                                                <img class="company__logo" src="{{$logo->image_url}}" alt="company logo">--}}
{{--                                            @endforeach--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
                      
                    </div>
                </section>
                <!-- End Left Section -->

            </div>
        </div>
       
    </article>
    <!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script crossorigin="anonymous" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>

        
<script>
   $('select').niceSelect();
  
   document.addEventListener("DOMContentLoaded", function () {
 
// make it as accordion for smaller screens
if (window.innerWidth < 992) {

    // close all inner dropdowns when parent is closed
    document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
        everydropdown.addEventListener('hidden.bs.dropdown', function () {
            // after dropdown is hidden, then find all submenus
            this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                // hide every submenu as well
                everysubmenu.style.display = 'none';
               
            });
        })
    });

    document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
        element.addEventListener('click', function (e) {

            let nextEl = this.nextElementSibling;
            if (nextEl && nextEl.classList.contains('submenu')) {
                // prevent opening link if link needs to open dropdown
                e.preventDefault();

                if (nextEl.style.display == 'block') {
                    nextEl.style.display = 'none';
                } else {
                    nextEl.style.display = 'block';
                }

            }
        });
    })
}
// end if innerWidth

});
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

    $(document).on('change','.sort',function (){
        var sort = this.value;

        var keyword = getUrlParameter('keyword');
        if(keyword){
            return window.location.href = "?sort="+sort+'&keyword='+keyword;
        }
        return window.location.href = "?sort="+sort;

        //
        // var router;
        //
        //
        // var url      = window.location.href;
        // var origin   = window.location.origin;
        //
        // var _array = url.split('/');
        //     var type = _array[_array.length-1];
        //
        //
        // if(sort == '0'){
        //     router = "local-stock-section-sort-name/"+type ;
        // }
        // if(sort == '1'){
        //     router = "local-stock-section-sort-view-count/"+type;
        // }
        //
        // var new_url = origin +'/'+ router;

    })
</script>
<script type="text/javascript">
$(document).on('change','.section', function(){
    var data = {
		section_id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-local-sub-sections-search') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')

        $('.slecs option').each(function(){
  
            if($(this).attr('data') != s.section.id){
                $(this).removeAttr('selected');
            }else{
                $(this).attr('selected', true);

                 $('select').niceSelect('update');
            }
        })

		$.each(s.datas,function(k,v){
        var html = '';
        html += `<div class="col-12">
            <div class="product__card">
                <div class="image__card">
                    <img alt='product__image'
                        src="${v.image_url}"/>
                </div>
                <div class="product__content"  style="min-width : auto">
                    <header class="product__title">
                        <a href="{{ url('local-stock-members') }}/${v.id}">  ${v.name}</a></header>
                </div>
                <div class="logo__main__card one-full-slider">`;

            $.each(s.ads,function(k,u) {
                if (v.id == u.system_ads_pages.slice(-1)[0].sub_id) {
                    html += `<img class="company__logo" src="${u.image_url}" alt="company logo">`;
                }
            });
            html += `</div>
                    </div>
                </div>`;
            $('.new__cards').append(html);
      
    })

            $.each(s.subs,function(k,v){

        $('.new__cards').append(`

        <div class="col-12">
            <div class="product__card">
                <div class="image__card">
                    <img alt='product__image'
                        src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>
                </div>
                <div class="product__content"  style="min-width : auto">
                    <header class="product__title">
                        <a href="{{ url('stock-fodder') }}/${v.id}">  ${v.name}</a></header>
                </div>
                <div class="logo__main__card one-full-slider lofo${v.id}">
                        
                </div>
            </div>
        </div>

        `);

        $.each(s.adsf,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.lofo'+v.id).append(`
                    
                
                    <img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">

                    `);
            }
           
        });
        
      
    })

  
    
	}});

    $('.one-full-slider').slick({
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
}).click(function (e) {
    e.preventDefault();
});
});


//$(document).on('keyup','.ser', function(e){
  //  var data = {
//		search : $(this).val(),
	//	_token     : $("input[name='_token']").val()
//	}

	//$.ajax({
//	url     : "{{ url('get-local-sub-sections-search-name') }}",
//	method  : 'post',
//	data    : data,
//	success : function(s,result){
//		$('.new__cards').html('');


//		$.each(s.datas,function(k,v){

	//	$('.new__cards').append(`


        {{--<div class="col-12">--}}
        {{--    <div class="product__card">--}}
        {{--        <div class="image__card">--}}
        {{--            <img alt='product__image'--}}
        {{--                src="${v.image_url}"/>--}}
        {{--        </div>--}}
        {{--        <div class="product__content"  style="min-width : auto">--}}
        {{--            <header class="product__title">--}}
        {{--                <a href="{{ url('local-stock-members') }}/${v.id}">  ${v.name}</a></header>--}}
        {{--        </div>--}}
        {{--        <div class="logo__main__card one-full-slider loooo${v.id}">--}}
        {{--                --}}
        {{--        </div>--}}
        {{--    </div>--}}
        {{--</div>--}}


      //  `);
      //  $.each(s.ads,function(k,u){
          //  if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
            //    $('.loooo'+v.id).append(`<img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">`);
          //   }

       // });

   // })

           // $.each(s.subs,function(k,v){

     //   $('.new__cards').append(`

        {{--<div class="col-12">--}}
        {{--    <div class="product__card">--}}
        {{--        <div class="image__card">--}}
        {{--            <img alt='product__image'--}}
        {{--                src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>--}}
        {{--        </div>--}}
        {{--        <div class="product__content"  style="min-width : auto">--}}
        {{--            <header class="product__title">--}}
        {{--                <a href="{{ url('stock-fodder') }}/${v.id}">  ${v.name}</a></header>--}}
        {{--        </div>--}}
        {{--        <div class="logo__main__card one-full-slider lofo${v.id}">--}}
        {{--                --}}
        {{--                </div>--}}
        {{--    </div>--}}
        {{--</div>--}}

      //  `);
       // $.each(s.adsf,function(k,u){
        //    if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
        //         $('.lofo'+v.id).append(`

                    {{--<img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">--}}

                    // `);
            // }

        // });


    // })
// $('.one-full-slider').not('.slick-initialized').slick({
//     dots: false,
//     infinite: true,
//     autoplay: true,
//     autoplaySpeed: 3000,
//     speed: 1000,
//     slidesToShow: 1,
//     adaptiveHeight: true,
//     pauseOnHover: true,
//     pauseOnFocus: true,
//     draggable: true,
//     arrows: false,
//     rtl: true,
// })


// 	}});
// });


$(document).on('change','.sort', function(){
    var data = {
        section_id : $('.section:checked').val(),
        sort : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-local-sub-sections-sorting') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')

        $('.slecs option').each(function(){
  
        if($(this).attr('data') != s.section.id){
            $(this).removeAttr('selected');
        }else{
            $(this).attr('selected', true);

             $('select').niceSelect('update');


        }


        })
        console.log(s.datas);
        // console.log('attr data'+$(this).attr('data'));
        // console.log('section id'+s.section.id);
		$.each(s.datas,function(k,v){

		$('.new__cards').append(`

        
        <div class="col-12">
            <div class="product__card">
                <div class="image__card">
                    <img alt='product__image'
                        src="{{asset('uploads/sections/sub/')}}/${v.image}"/>
                </div>
                <div class="product__content"  style="min-width : auto">
                    <header class="product__title">
                        <a href="{{ url('local-stock-members') }}/${v.id}">  ${v.name}</a></header>
                </div>
                <div class="logo__main__card one-full-slider loooo${v.id}">
                        
                        </div>
            </div>
        </div>

   
        `);
        $.each(s.ads,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.loooo'+v.id).append(`
                    
                
                    <img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">

                    `);
            }
           
        });
      
    })

            $.each(s.subs,function(k,v){

        $('.new__cards').append(`

        <div class="col-12">
            <div class="product__card">
                <div class="image__card">
                    <img alt='product__image'
                        src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>
                </div>
                <div class="product__content"  style="min-width : auto">
                    <header class="product__title">
                        <a href="{{ url('stock-fodder') }}/${v.id}">  ${v.name}</a></header>
                </div>
                <div class="logo__main__card one-full-slider lofo${v.id}">
                        
                        </div>
            </div>
        </div>

        `);
        $.each(s.adsf,function(k,u){
            if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                $('.lofo'+v.id).append(`
                    
                
                    <img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">

                    `);
            }
           
        });
      
    })
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




$(document).on('keyup','.ser', function(e){

        var section = "{{$section->type}}";
        var value = $(this).val();
    $.ajax({
        url     : "{{ url('search-index') }}" + '/'+section + '?search='+value,
        method  : 'GET',
        // data    : data,
        success : function(result){

            $('.new__cards').html('');


            $.each(result.data,function(key,val){
                var html = '';
                html += '<div class="col-12">'+
                            '<div class="product__card">'+
                                '<div class="image__card">'+
                                    '<img alt="product__image" src="'+val.image+'"/>'+
                                '</div>'+
                                '<div class="product__content"  style="min-width : auto">'+
                                    '<header class="product__title">'+
                                        '<a href="'+val.link+'">'+val.name+'</a>'+
                                    '</header>'+
                                '</div>'+
                                '<div class="logo__main__card one-full-slider">';

                                $.each(val.logos,function(k,u){
                                    html += '<img class="company__logo" src="'+u.image_url+'" alt="company logo">';
                                  });

                        html +=  '</div>'+
                            '</div>'+
                        '</div>';


                $('.new__cards').append(html);
            });
        }
    });
});

               // $.each(s.ads,function(k,u){
                   // if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
                        //$('.loooo'+v.id).append(`<img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">`);
                    // }

                // });

            // })

            // $.each(s.subs,function(k,v){
            //
            //     $('.new__cards').append(`

        {{--<div class="col-12">--}}
        {{--    <div class="product__card">--}}
        {{--        <div class="image__card">--}}
        {{--            <img alt='product__image'--}}
        {{--                src="{{asset('uploads/sections/avatar/')}}/${v.image}"/>--}}
        {{--        </div>--}}
        {{--        <div class="product__content"  style="min-width : auto">--}}
        {{--            <header class="product__title">--}}
        {{--                <a href="{{ url('stock-fodder') }}/${v.id}">  ${v.name}</a></header>--}}
        {{--        </div>--}}
        {{--        <div class="logo__main__card one-full-slider lofo${v.id}">--}}

        {{--                </div>--}}
        {{--    </div>--}}
        {{--</div>--}}

        // `);
        //         $.each(s.adsf,function(k,u){
        //             if(v.id == u.system_ads_pages.slice(-1)[0].sub_id){
        //                 $('.lofo'+v.id).append(`


                    {{--<img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">--}}

                //     `);
                //     }
                //
                // });


            // })
            // $('.one-full-slider').not('.slick-initialized').slick({
            //     dots: false,
            //     infinite: true,
            //     autoplay: true,
            //     autoplaySpeed: 3000,
            //     speed: 1000,
            //     slidesToShow: 1,
            //     adaptiveHeight: true,
            //     pauseOnHover: true,
            //     pauseOnFocus: true,
            //     draggable: true,
            //     arrows: false,
            //     rtl: true,
            // })






</script>
@endsection