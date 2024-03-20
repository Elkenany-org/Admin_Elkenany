<!DOCTYPE html>
<html lang="ar">

<head>



    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NYH035MQGZ"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-NYH035MQGZ');
      console.log(' Google Analytics Is Fired')
    </script>



    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta content="This is Description Area" name="description">
    <!-- Icon -->
    <link href="{{asset('Front_End/images/favicon.png')}}" rel="icon">
    <!-- Title -->
    <title> {{$section->name}}</title>
    <!-- Bootstrap CSS -->
    <link href="{{asset('Front_End/css/vendors/bootstrap.css')}}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{asset('Front_End/css/vendors/all.min.css')}}" rel="stylesheet">
    <!-- slick -->
    <link href="{{asset('Front_End/vendors/slick/slick.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('Front_End/vendors/slick/slick-theme.css')}}" rel="stylesheet" type="text/css"/>
    <!-- My CSS -->
    <link href="{{asset('Front_End/css/styles.css')}}" rel="stylesheet">
    <script src="{{asset('Front_End/js/vendors/apexcharts.js')}}"></script>
    <style type="text/css">
img.image {
    width: 75px;
    height: 75px;
    border-radius: 0.375rem;
}
</style>

</head>

<body>
<div class="main__content__body">
    <!-- Start Loading Screen -->
    <article class="loading-screen">
        <div class="chicken-loader">
            <span></span>
        </div>
    </article>
    <!-- End Loading Screen -->

    <!-- Start button to top -->
    <button class="btn_top" id="myBtn">
        <span class="arrow"></span>
    </button>
    <!-- End button to top -->

    @include('../fronts.sidebar')

    
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
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container st-1 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">{{$section->name}} </h1>
        </div>
    </div>

    <!-- Start Search Box -->
    <article class="inner-search-box d-none d-sm-none d-md-none d-lg-block ">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-5">
                    <i class="fas fa-puzzle-piece"></i>
                    <h4 class="title">القطاع:</h4>
                    <select  class="form-control" onChange="window.location.href=this.value">
                        @foreach($secs as $value)
                        <option value="{{ route('front_local_sections',$value->type) }}"{{ isset($section) && $section->section_id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach   
                    </select>
                </section>
                <section class="tabs tabs-5">
                    <i class="fas fa-puzzle-piece"></i>
                    <h4 class="title">البورصة:</h4>
                    <select  class="form-control" onChange="window.location.href=this.value">
                        @foreach($subs as $value)
                        <option value="{{ route('front_fodder_stocks',$value->id) }}"{{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
                <section class="tabs tabs-5">
                    <i class="far fa-calendar-alt"></i>
                    <h4 class="title">التاريخ:</h4>
                    <input class="date "  value="{{isset($_GET['date']) ? $_GET['date'] : ''}}" name="date" type="date">
                </section>
                <section class="tabs tabs-5">
                    <i class="fas fa-balance-scale"></i>
                    <h4 class="title">مقارنة</h4>
                    <a class="stats__btn" href="{{ route('front_fodder_comprison',$section->id) }}">قارن الأن</a>
                </section> 
                <section class="tabs tabs-5">
                    <i class="fas fa-poll"></i>
                    <h4 class="title">احصائيات:</h4>
                    <a href="{{ route('front_fodder_statistic_members',$section->id) }}" class="stats__btn">احصائيات</a>
                </section>
            </form>
        </div>
    </article>
    <!-- End Search Box -->
    @include('../parts.alert')

    <!-- Start Accordion Section -->
    <section class="special__accordion container d-lg-none d-block d-md-block">
        <button aria-controls="collapseExample" aria-expanded="false" class="main-title" data-toggle="collapse"
                href="#accordion"
                id="show-hide-accordion" role="button">
            حدد بحثك
            <i class="icon-s fas fa-search"></i>
        </button>
        <div class="accordion collapse show" id="accordion">
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
                <div aria-labelledby="headingOne" class="collapse show" data-parent="#accordion" id="collapseOne">
                    <div class="card-body">
                        <ul>
                        @foreach($secs as $value)
                            <li>
                                <a>
                                    <input onChange="window.location.href=this.value" id="tab-1-val-{{$value->id}}" name="tab-1-values" type="radio" value="{{ route('front_fodder_stocks',$value->id) }}"
                                    {{ isset($section) && $section->section_id == $value->id ? 'checked'  :'' }}>
                                    <label for="tab-1-val-{{$value->id}}">{{$value->name}}</label>
                                </a>
                            </li>
                        @endforeach   
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingSix">
                    <h5 class="mb-0">
                        <button aria-controls="collapseSix" aria-expanded="true" class="btn btn-link"
                                data-target="#collapseSix" data-toggle="collapse">
                            <i class="fas fa-puzzle-piece"></i>
                            البورصة
                        </button>
                    </h5>
                </div>
                <div aria-labelledby="headingSix" class="collapse" data-parent="#accordion" id="collapseSix">
                    <div class="card-body">
                    <ul class="long__list">
                            @foreach($subs as $value)
                                <li>
                                    <a>
                                        <input  onChange="window.location.href=this.value" id="tab-6-val-{{$value->id}}" name="tab-6-values" type="radio" value="{{ route('front_fodder_stocks',$value->id) }}"
                                        {{ isset($section) && $section->id == $value->id ? 'checked'  :'' }}>
                                        <label for="tab-6-val-{{$value->id}}">{{$value->name}}</label>
                                    </a>
                                </li>
                            @endforeach  
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button aria-controls="collapseThree" aria-expanded="false" class="btn btn-link collapsed"
                                data-target="#collapseThree" data-toggle="collapse">
                            <i class="far fa-calendar-alt"></i>
                            التاريخ
                        </button>
                    </h5>
                </div>
                <div aria-labelledby="headingThree" class="collapse" data-parent="#accordion" id="collapseThree">
                    <div class="card-body">
                        <input class="date "  value="{{isset($_GET['date']) ? $_GET['date'] : ''}}" name="date" type="date">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFour">
                    <h5 class="mb-0">
                        <button aria-controls="collapseFour" aria-expanded="false" class="btn btn-link collapsed"
                                data-target="#collapseFour" data-toggle="collapse">
                            <i class="fas fa-balance-scale"></i>
                            مقارنة
                        </button>
                    </h5>
                </div>
                <div aria-labelledby="headingFour" class="collapse" data-parent="#accordion" id="collapseFour">
                    <div class="card-body">
                        <ul>
                            <li><a class="stats__btn" href="{{ route('front_fodder_comprison',$section->id) }}">قارن الان</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingFive">
                    <h5 class="mb-0">
                        <button aria-controls="collapseFive" aria-expanded="false"
                                class="btn btn-link collapsed" data-target="#collapseFive"
                                data-toggle="collapse">
                            <i class="fas fa-poll"></i>
                            الاحصائيات
                        </button>
                    </h5>
                </div>
                <div aria-labelledby="headingFive" class="collapse" data-parent="#accordion"
                     id="collapseFive">
                    <div class="card-body">
                        <a class="stats__btn" href="{{ route('front_fodder_statistic_members',$section->id) }}">احصائيات</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Accordion Section -->


    <div class="global__container">

        <article class="table__header__filter special__container">
            <section class="head new__cards__header">
                <div class="card">
                    <div class="card-header" id="itemsSelect2">
                        <button aria-controls="collapseItemsSelect2" aria-expanded="true" class="card__button companyLabel"
                                data-target="#collapseItemsSelect2" data-toggle="collapse">
                            الشركة
                        </button>
                    </div>
                    <div aria-labelledby="itemsSelect2" class="collapse" data-parent="#accordion"
                         id="collapseItemsSelect2">
                        <div class="card-body">
                            <div class="multiselect multiselect-accordion" id="multiselect-companies-accordion">
                                <div class="checkboxes" id="checkboxes-companies-accordion">
                                    <div class="company__search">
                                        <label for="searchCompany">
                                            <input class="searchInput" id="searchCompany" name="search"
                                                   placeholder="ابحث عن"
                                                   type="search">
                                        </label>
                                    </div>
                                    <div class="all__labels">
                                    <form action="" method="get" >
{{--                                        {{route('front_fodder_companies_stock',$section->id)}}--}}
{{--                                        {{csrf_field()}}--}}
                                        @foreach($fodss as $value)
                                            <label class="company__filter__label" for="company-accordion-{{$value->id}}">
                                                <input class="checks checks-company-accordion comp" id="company-accordion-{{$value->id}}"
                                                    name="company_id" data-name="{{$value->Company->name}}" value="{{$value->Company->id}}" type="radio" {{isset($_GET['company_id']) ? $_GET['company_id'] == $value->Company->id ? 'checked' : '' : ''}}>{{$value->Company->name}}</label>
                                        @endforeach
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="head new__cards__header">
                <div class="card">
                    <div class="card-header" id="itemsSelect">
                        <button aria-controls="collapseItemsSelect" aria-expanded="true" class="card__button fodder_label"
                                data-target="#collapseItemsSelect" data-toggle="collapse">
                            الصنف
                        </button>
                    </div>
                    <div aria-labelledby="itemsSelect" class="collapse" data-parent="#accordion"
                         id="collapseItemsSelect">
                        <div class="card-body">
                            <div class="multiselect multiselect-accordion" id="multiselect-types-accordion">
                                <div class="checkboxes" id="checkboxes-types-accordion">
                                    <div class="company__search">
                                        <label for="searchTypes">
                                            <input class="searchInput" id="searchTypes" name="search"
                                                   placeholder="ابحث عن"
                                                   type="search">
                                        </label>
                                    </div>
                                    <div class="all__labels">
                                        <form action="" method="get">
{{--                                            {{route('front_fodder_feeds',$section->id)}}--}}
{{--                                        {{csrf_field()}}--}}
                                        @foreach($feeds as $value)
                                        <label class="type__filter__label" for="type-accordion-{{$value->id}}">
                                            <input class="checks checks-type-accordion slec" id="type-accordion-{{$value->id}}"
                                                   name="fodder_id" type="radio" data-name="{{$value->name}}" value="{{$value->id}}"{{ isset($fod) && $fod->id == $value->id ? 'checked'  :'' }}>
                                                   {{$value->name}}</label>
                                        @endforeach
                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </article>

        <section class="col-md-12 text-center container st-1" style="margin-bottom: 10px;border-radius: 1px 1px 20px 20px;background-color: #FFAA00;height:55px;display: {{$status != 'new' ? '' : 'none'}}; " >
            <div class="custom__breadcrumb" style="line-height: 55px">
                <h6 class="page__title" style="line-height: 55px;"><strong>تنوية: تم اخر تحديث في يوم  {{$date}}</strong></h6>
            </div>
        </section>
        <!-- Start Table 7 cols with chart -->
        <section class="table__container table-chart-7 special__container">
            <section class="table__head">
                <span class="title">الشركة</span>
                <span class="title"> السعر</span>
                <span class="title">الصنف</span>
                <span class="title"> مقدار التغير</span>
                <span class="title title__chart  d-lg-flex d-none">اتجاه السعر</span>

            </section>
            <!--  add row__shadow when rows length > = 3  -->
            <div class="table__body row__shadow scrolling-pagination" id="get-list-view">
            @if(count($movessort) > 0)
                @foreach($movessort as $key => $value)
                    @if($value->price > '0')
                        <!-- Start One Row  -->
                        <section class="table__row row__image ads">
                            <a href="{{ route('front_company',$value->Company->id) }}" class="cell__container">
                                <div class="cell__content__with__image">
                                    <img class="image" src="{{$value->Company->image_url}}" alt="logo" style="width: 150px;height: 120px">
                                    <span class="name"> {{$value->Company->name}}</span>
                                </div>
                            </a>
                            <div class="wall"></div>
                            <section class="cell__container">
                                <span class="cell__content">{{round($value->price, 2)}} جنية</span>
                            </section>

                            <div class="wall"></div>
                            <section class="cell__container">
                                <span class="cell__content"> {{$value->StockFeed->name}}</span>
                            </section>


                            <div class="wall"></div>
                            <section class="cell__container">
                                @if($value->change < '0' )
                                <span class="cell__content down">{{round($value->change, 2)}}</span>
                                <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                @endif
                                @if($value->change > '0' )
                                <span class="cell__content up">+{{round($value->change, 2)}}</span>
                                <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                @endif
                                @if($value->change == '0' )
                                <span class="cell__content">{{round($value->change, 2)}}</span>
                                <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                @endif
                            </section>


                            <div class="wall  d-lg-block d-none"></div>
                            <section class="data charts d-lg-block d-none">
                                <div class="chart{{$value->id}}"></div>
                            </section>
                            <script>
                                var changes = [];
                                var dates = [];

                                <?php foreach($value->FodderStock->movements()['changes'] as  $ch){ ?>
                                changes.push('<?php echo $ch; ?>');
                                <?php } ?>

                                <?php foreach($value->FodderStock->movements()['dates'] as $da){ ?>
                                dates.push('<?php echo $da; ?>');
                                <?php } ?>

                                new ApexCharts(document.querySelector(".chart{{$value->id}}"), {
                                    series: [{
                                        name: "",
                                        data: changes,
                                    }],
                                    chart: {
                                        height: 120,
                                        width: '100%',
                                        type: 'line',
                                        zoom: {
                                            enabled: false
                                        },
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    yaxis: {
                                        show: false
                                    },
                                    dataLabels: {
                                        enabled: false
                                    },
                                    stroke: {
                                        curve: 'straight',
                                        colors: ['#008000'],
                                        width: 2,
                                    },
                                    title: {
                                        text: '',
                                        align: 'left'
                                    },
                                    grid: {
                                        show: false,
                                        row: {
                                            opacity: 0.5
                                        },
                                    },
                                    xaxis: {
                                        categories: dates
                                    },
                                    markers: {
                                        colors: ['#000']
                                    },
                                    tooltip: {
                                        fillSeriesColor: true,
                                        theme: true,
                                        style: {
                                            fontSize: '15px'
                                        },
                                        onDatasetHover: {
                                            highlightDataSeries: false,
                                        },
                                    }
                                }).render();

                            </script>
                        </section>
                        <!-- End One Row  -->
                    @endif
                @endforeach
            @endif
                @if(count($moves) > 0)
                    @include('fodderstock::fronts.section_data_table')
                    <div class="ajax-load text-center" style="display:none">
                        <p>Loading More</p>
                    </div>
                @endif
            </div>
        </section>
        <!-- End Table -->


        <!--   ///////////////////////////////////////////////////////////////////////////////////////////////////     -->
    </div>


    <!-- Start Caution -->
    <article class="caution container">
    <svg fill="#FFAA00" height="50px" viewBox="0 0 24 24" width="50px" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0h24v24H0V0z" fill="none"/>
                <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
            </svg>
        <p class="caution-description">هذه الاسعار استرشادية عن طريق المورد وتختلف حسب الجودة والكمية وطرق
            السداد ووقت التنفيذ</p>
    </article>
    <!-- End Caution -->
</div>

@include('../fronts.footer')


<!-- Start Scripts -->
<!-- jQuery first, then Popper.js, then Bootstrap JS v4.5 -->
<script src="{{asset('Front_End/js/vendors/jquery-3.5.1.slim.min.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/popper.min.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/bootstrap.min.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/jquery-3.5.1.js')}}"></script>
<!-- Slick -->
<script src="{{asset('Front_End/vendors/slick/slick.min.js')}}"></script>
<script src="{{asset('Front_End/js/slick_initialising.js')}}"></script>
<!--          Select MENU Styling          -->
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<!-- My JavaScript -->
<script src="{{asset('Front_End/js/global.js')}}"></script>
<script src="{{asset('Front_End/js/fodder_stock_exchange.js')}}"></script>
<script>

    $(document).ready(function(){

    });




$(document).on('change','.date', function(){
    var urlParams = new URLSearchParams(window.location.search);
    var link = '';
    // var urlPath = '?page=' + page;  && urlParams.get('date') != null
    if(urlParams != "" ){
        link += "&date="+$(this).val();
    }else{
        link = "?date=" +$(this).val();
    }
     var   url =  window.location.href + link;

        window.location.href = url


});
    $('select').niceSelect();
    $(document).on('change','.slec', function(){

    $(this).closest('form').submit();

    });

    $(document).on('change','.comp', function(){

    $(this).closest('form').submit();

    });
</script>
<script>
    if(typeof($('input[name="company_id"]:checked').attr('data-name')) != 'undefined' ){
        $('.companyLabel').text($('input[name="company_id"]:checked').attr('data-name'));
    }
    if(typeof($('input[name="fodder_id"]:checked').attr('data-name')) != 'undefined' ){
        $('.fodder_label').text($('input[name="fodder_id"]:checked').attr('data-name'));
    }
</script>
<script>

    function isElementVisible(elem)
    {
        var $elem = $(elem);
        var $window = $(window);

        var docViewTop = $window.scrollTop();
        var docViewBottom = docViewTop + $window.height();

        var elemTop = $elem.offset().top;
        var elemBottom = elemTop + $elem.height();

        return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
    }
</script>

<script type="text/javascript">
    var page = 1;
    $('.scrolling-pagination').scroll(function() {
        if($('.scrolling-pagination').scrollTop() + $(window).height() > $(document).height()) {

               if(page <= {{$moves->lastPage()}}){
                   var e = $('.table_all:last');
                   if (isElementVisible(e)) {
                       var last_index = $(".table_all").last().index();

                       if(last_index === e.index()){
                           page++;
                           setInterval(loadMoreData(page), 10000 );
                       }
                   }
               }
        }
    });


    function loadMoreData(page){
        var urlParams = new URLSearchParams(window.location.search);
        var addition;
        var urlPath = '?page=' + page;

        if(urlParams != null && urlParams.get('company_id') != null){
            addition = '&company_id='+urlParams.get('company_id');
            urlPath += addition;
        }
        if(urlParams != null && urlParams.get('fodder_id') != null){
            addition = '&fodder_id='+urlParams.get('fodder_id');
            urlPath += addition;
        }
        if(urlParams != null && urlParams.get('date') != null){
            addition = '&date='+urlParams.get('date');
            urlPath += addition;
        }

        $.ajax(
            {
                url: urlPath,
                type: "get",
                beforeSend: function()
                {
                    $('.ajax-load').show();
                }
            })
            .done(function(data)
            {
                // console.log(data['html']);
                if(data.html == " "){
                    $('.ajax-load').html("No more records found");
                    return;
                }
                $('.ajax-load').hide();
                $("#get-list-view").append(data.html);
            })
            .fail(function(jqXHR, ajaxOptions, thrownError)
            {
                console.log('server not responding...');
                // console.log('jqXHR...'+JSON.stringify(jqXHR));
                // console.log('ajaxOptions...'+ajaxOptions);
                // console.log('thrownError...'+thrownError);
            });
    }
</script>
<!-- End Scripts -->
</body>

</html>
