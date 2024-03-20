
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<link rel="stylesheet" href="{{asset('Front_End/css/companies_guide.css')}}">
@endsection
@section('content')

{{csrf_field()}}
<!-- Start Search Box -->
<article class="inner-search-box d-lg-block d-none">
    <div class="container holder">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
            <div class="edge"></div>
            <section class="tabs">
                <i class="fas fa-list"></i>
                <h4 class="title">القطاع:</h4>
                <select class="form-control" onChange="window.location.href=this.value">
                    @foreach($secs as $value)
                    <option value="{{ route('front_section_magazines_one',$value->id) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                    @endforeach  
                </select>
            </section>
            <section class="tabs">
                <i class="fas fa-sort-amount-down"></i>
                <h4 class="title">الترتيب:</h4>
                <select name="sort" onChange="window.location.href=this.value">
                    <option value="{{ route('front_section_magazines_one',$section->id) }}" {{ isset($sort) && $sort == "0" ? 'selected'  :'' }}> الابجدي</option>
                    <option value="{{ route('front_section_sort_magazines',$section->id) }}"{{ isset($sort) && $sort == "1" ? 'selected'  :'' }}> َالاكثر تداولا</option>
                </select>
            </section>
        </form>
    </div>
</article>
<!-- End Search Box -->
<!-- Start Global Content -->
<article class="global-content">
    <div class="container holder">
        <div class="row">
            <!-- Start Right Section -->
            <section class="right col-lg-3 col-md-7 col-sm-12">
                <h2 id="show-hide-accordion" class="main-title">
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
                            data-parent="#accordion">
                            <div class="card-body">
                                <ul>
                                @foreach($secs as $value)
                                    <li>
                                        <a>
                                            <input type="radio" id="tab-1-val-1"  class="sec" name="tab-1-values" value="{{$value->id}}"
                                                >
                                            <label for="tab-1-val-1">{{$value->name}}</label>
                                        </a>
                                    </li>
                                @endforeach  
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Right Section -->

            <!-- Start Left Section -->
            <section class="left col-lg-9 col-md-7 col-sm-12">
                <section class="title-box">
                    <h2 class="title">{{$section->name}}</h2>
                    <span class="sections-number">{{count($section->SubSections)}} قسم</span>
                </section>
                <section class="all-cards">
                    @foreach($sections as $value)
                        <!-- Start One Card -->
                        <a href="{{ route('front_magazines',$value->id) }}" class="regular-cards one-card">
                            <section class="logo-box">
                                <img class="card-image" src="{{asset('uploads/sections/avatar/'.$value->image)}}" alt="card image">
                            </section>
                            <section class="content-box">
                                <h2>  {{$value->name}}</h2>
                                <span class="companies-number">{{count($value->Magazine)}} {{$value->type}}</span>
                            </section>
                            <section class="one-full-slider left-box">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
                            </section>
                        </a>
                        <!-- End One Card -->
                    @endforeach
                </section>
            </section>
            <!-- End Left Section -->

        </div>
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
		section_id : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-section-search-magazines-to-front') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.all-cards').html('')

        $('.sections-number').html(s.length + 'قسم')
		$.each(s,function(k,v){
            $('.title').html(v.section.name)
            
        
		$('.all-cards').append(`

        <a href="{{ url('magazines-magazines') }}/${v.id}" class="regular-cards one-card">
            <section class="logo-box">
                <img class="card-image" src="{{asset('uploads/sections/avatar/')}}/${v.image}" alt="card image">
            </section>
            <section class="content-box">
                <h2>  ${v.name}</h2>
                <span class="companies-number">${v.magazine.length} شركة</span>
            </section>
            <section class="one-full-slider left-box">
                <img class="company-logo" src="{{asset('Front_End/images/main_logo.png')}}" alt="company logo">
            </section>
        </a>
		`);
	})
	}});
});
</script>
@endsection