@extends('layouts.front')
    @section('style')
    <!-- My CSS -->
    <title>   حركة السفن </title>
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
    <div class="breadcrumb__container st-1 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">حركة السفن</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

    <!-- Start Search Box -->
    <article class="inner-search-box d-lg-block d-none">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-2">
                <i class="far fa-calendar-alt"></i>
                    <h4 class="title">تاريخ الوصول:</h4>
                    <input class="date slec" value="{{$date}}" name="date" type="date">
                </section>
                <section class="tabs tabs-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <h4 class="title">إحصائيات:</h4>
                    <a href="{{route('front_ships_statistc')}}" class="stats__btn">إحصائيات</a>
                </section>
            </form>
        </div>
    </article>
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
                    <input class="date slec" value="{{$date}}" name="date" type="date">
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
                            الإحصائيات
                        </button>
                    </h5>
                </div>
                <div aria-labelledby="headingFive" class="collapse" data-parent="#accordion"
                     id="collapseFive">
                    <div class="card-body">
                    <a href="{{route('front_ships_statistc')}}" class="stats__btn">إحصائيات</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Accordion Section -->
    <!-- End Search Box -->
    <!-- Start Global Content -->
    <section class="table__container table-7 special__container">
        <section class="table__head">
            <span class="title">إسم السفينة</span>
            <span class="title"> تاريخ التوجيه</span>
            <span class="title">الحمولة / طن</span>
            <span class="title">النوع</span>
            <span class="title">المنشأ</span>
            <span class="title"> تاريخ الوصول</span>
            <span class="title">الجهة المستوردة</span>
            <span class="title"> الوكيل الملاحي</span>
           
            <span class="title">ميناء التفريغ</span>
          
        </section>
        <div class="table__body row__shadow scrolling-pagination" id="get-list-view">
            @include('internationalstock::fronts.data')
            <div class="ajax-load text-center" style="display:none">
                <p>Loading More</p>
            </div>
        </div>

    </section>
    <!-- End Global Content -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();

    $(document).on('change','.date', function(){

    var link = "{{url('all-ships-traffic')}}" + "?date=" +$(this).val()
            window.location.href = link

    });

</script>

<script type="text/javascript">
    var page = 1;
    $('.scrolling-pagination').scroll(function() {
        if($('.scrolling-pagination').scrollTop() + $(window).height() > $(document).height()) {
            page++;
            if(page <= {{$ships->lastPage()}}){
                loadMoreData(page);
            }
        }
    });


    function loadMoreData(page){
        var curURL = window.location.href;
        $.ajax(
            {
                url: curURL +'?page=' + page,
                type: "get",
                beforeSend: function()
                {
                    $('.ajax-load').show();
                }
            })
            .done(function(data)
            {
                console.log(data['html']);
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
            });
    }
</script>
@endsection