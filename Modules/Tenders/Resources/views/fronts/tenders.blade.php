
@extends('layouts.front')
@section('style')
<title>  المناقصات</title>
<style type="text/css">

.ajax-load{

background: #fff;
padding: 10px 0px;
width: 100%;
text-align:center;

}

</style> 
@endsection
@section('content')

<!-- Start Header -->
<header class="banner__header">      
    <div class="container-fluid one-full-slider">
        @foreach($ads as $ad)
            <a href="{{$ad->link}}" target="_blank"><img alt="banner" class="banner" src="{{asset('uploads/ads/'.$ad->image)}}"></a>
        @endforeach 
    </div>
</header>
<article class="partners slider my-2">
    <div class="container-fluid logos__holder">
     <section class="partners-slider">
        @foreach($logos as $logo)
            <div class="item">
            <a href="{{$logo->link}}" class="logo-holder"><img alt="partner logo" src="{{asset('uploads/ads/'.$logo->image)}}"></a>
            <div class="item">
            </div>
        @endforeach 
     </section>
    </div>
</article>
    <!-- End Partners -->
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container nt-0 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">المناقصات</h1>
        </div>
    </div>
    <!-- End breadcrumb -->
<!-- Start Search Box -->
<article class="inner-search-box d-lg-block d-none">
    <div class="container">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
            <section class="tabs  tabs-2">
                <i class="fas fa-list"></i>
                <h4 class="title">القطاع:</h4>
                <select class="form-control slecs" onChange="window.location.href=this.value">
                    @foreach($secs as $value)
                    <option class="op{{$value->id}}" data="{{$value->id}}" value="{{ route('front_section_tenders',$value->type) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs tabs-2">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title sect">الترتيب:</h4>
                <select name="sort" onChange="window.location.href=this.value">
                    <option value="{{ route('front_section_tenders',$section->type) }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الابجدي</option>
                    <option value="{{ route('front_section_tenders_view',$section->type) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}> َالاكثر تداولا</option>
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
                </section>
            </section>
            <!-- End Right Section -->

            <!-- Start Left Section -->
            <section class="main__left__container col-lg-8 col-md-12 col-sm-12">
                <div class="new__cards row">
                                        
                    @foreach($tenders as $value)

                    <div class="col-12">
                        <div class="product__card  regular__hover">
                            <div class="image__card">
                                <img alt='product-image'
                                src="{{asset('uploads/tenders/avatar/'.$value->image)}}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ route('front_one_tenders',$value->id) }}">
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
                <div class="row text-center">
                    <div class="col-sm-12 text-center">
                    {{$tenders->links()}}
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


$(document).on('change','.sec', function(){
    var data = {
		id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-tenders-search') }}",
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
                        <div class="product__card  regular__hover">
                            <div class="image__card">
                                <img alt='product-image'
                                src="{{asset('uploads/tenders/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('one-tenders') }}/${v.id}">
                                    ${v.title}
                                    </a>
                                </header>
                                <section class="product__bottom justify-content-end">
                                    <section class="date__box">
                                        <span class="date">  ${v.created_at}</span>
                                    </section>
                                </section>
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
	url     : "{{ url('get-section-tenders-search-more') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
        console.log(s);
        $ele.fadeOut().remove();
		$.each(s.datas,function(k,v){
            
		$('.new__cards').append(`
        <div class="col-12">
                        <div class="product__card  regular__hover">
                            <div class="image__card">
                                <img alt='product-image'
                                src="{{asset('uploads/tenders/avatar/')}}/${v.image}"/>
                            </div>
                            <div class="product__content">
                                <header class="product__title">
                                    <a href="{{ url('one-tenders') }}/${v.id}">
                                    ${v.title}
                                    </a>
                                </header>
                                <section class="product__bottom justify-content-end">
                                    <section class="date__box">
                                        <span class="date">  ${v.created_at}</span>
                                    </section>
                                </section>
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