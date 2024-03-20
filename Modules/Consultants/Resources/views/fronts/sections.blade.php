
@extends('layouts.front')
@section('style')
<title>     الإستشاريين قسم  {{$major->name}} </title>
@endsection
@section('content')

 
{{csrf_field()}}

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

    <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
            <h1 class="page__title"> الإستشاريين</h1>
        </div>
    </div>
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
                    <option  data="{{$value->id}}" value="{{ route('front_sections',$value->type) }}"{{ isset($major) && $major->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs tabs-3">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title">الترتيب:</h4>
                <select name="sort" class="sort">
                    <option value="" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الابجدي</option>
                    <option value="1"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}> َالاكثر تداولا</option>
                </select>
            </section>
        </form>
    </div>
</article>
<!-- End Search Box -->

<!-- Start Global Content -->
<article class="global__container container">
        <div class="row">

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
                                            {{ isset($major) && $major->id == $value->id ? 'checked'  :'' }}>
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
            <section class="left col-lg-9 col-md-7 col-sm-12">
                <div class="new__cards row">
                    @if(!empty($major->SubSections))
                        @foreach($sections as $value)

                        <div class="col-12">
                            <div class="product__card regular__hover">
                                <div class="image__card">
                                    <img alt='product__image'
                                        src="{{asset('uploads/majors/avatar/'.$value->image)}}"/>
                                </div>
                                <div class="product__content">
                                    <header class="product__title">
                                        <a href="{{ route('front_doctors',$value->id) }}">
                                        {{$value->name}}
                                        </a>
                                    </header>
                                    <section class='product__info'>
                                        <p class="note">
                                        {{count($value->Doctor)}} اخصائي
                                        </p>
                                    </section>
                                </div>
                                <div class="logo__main__card one-full-slider">
                                    @foreach($value->logooos() as $logo)
                                        <img class="company__logo" src="{{asset('uploads/full_images/'.$logo->image)}}" alt="company logo">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                      
                        @endforeach
                    @endif
                </div>
            </section>
            <!-- End Left Section -->

    </div>
</article>
    <!-- End Global Content -->

@endsection


@section('script')

<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>

<script>
    $('select').niceSelect();

$(document).on('change','.sort', function(){

var link = "{{url('major',$major->type)}}" + "?sort=" +$(this).val()
        window.location.href = link

});
</script>

<script type="text/javascript">
$(document).on('change','.sec', function(){
    var data = {
		section_id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-sub-section-consultant') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
        $('.slecs option').each(function(){
  
  if($(this).attr('data') != s.seco.id){
      $(this).removeAttr('selected');
  }else{
      $(this).attr('selected', true);

      $('select').niceSelect('update');


  }


})

		$.each(s.datas,function(k,v){
            
        
		$('.new__cards').append(`
        <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img alt='product__image'
                                     src="{{asset('uploads/majors/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('consultant/major/section') }}/${v.id}">
                                    ${v.name}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note">
                                    ${v.doctor.length} اخصائي
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
                $('.loooo'+v.id).append(`
                    
                
                    <img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">

                    `);
            }
           
        });
    
    });

	}});
    
});


///search

$(document).on('keyup','.ser', function(e){
    var data = {
        search : $(this).val(),
        section_id : {{$major->id}},
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-search-name-in-majors') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.new__cards').html('')
   
		$.each(s.datas,function(k,v){
    
        
		$('.new__cards').append(`

        <div class="col-12">
                        <div class="product__card regular__hover">
                            <div class="image__card">
                                <img alt='product__image'
                                     src="{{asset('uploads/majors/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('consultant/major/section') }}/${v.id}">
                                    ${v.name}
                                    </a>
                                </header>
                                <section class='product__info'>
                                    <p class="note">
                                    ${v.doctor.length} اخصائي
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
                $('.loooo'+v.id).append(`
                    
                
                    <img class="company__logo" src="{{asset("uploads/full_images/")}}/${u.image}" alt="company logo">

                    `);
            }
           
        });
    
    });

	}});
    
});

</script>
@endsection