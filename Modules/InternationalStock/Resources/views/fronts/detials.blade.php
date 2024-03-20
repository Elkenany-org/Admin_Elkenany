
@extends('layouts.front')
@section('style')
<title>  تفاصيل  حركة السفن </title>
@endsection
@section('content')

<!-- Start Header -->

 <!-- Start breadcrumb -->
 <div class="breadcrumb__container st-1 container">
        <div class="custom__breadcrumb">
            <h1 class="page__title">إحصائيات حركة السفن</h1>
        </div>
    </div>
    <!-- End breadcrumb -->

<!-- Start Search Box -->
<article class="inner-search-box d-none d-sm-none d-md-none d-lg-block">
    <div class="container ">
        <form class="box-tabs">
            <div class="search-box-overlay"></div>
            <section class="tabs tabs-2">
                <i class="far fa-calendar-alt"></i>
                <h4 class="title">من:</h4>
                <input class="from date" name="from" value="{{isset($_GET['from']) ? $_GET['from'] : ''}}" type="date">
            </section>
            <section class="tabs tabs-2">
                <i class="far fa-calendar-alt"></i>
                <h4 class="title">إلى:</h4>
                <input class="to date" name="to" value="{{isset($_GET['to']) ? $_GET['to'] : ''}}" type="date">
            </section>
        </form>
    </div>
</article>
<!-- End Search Box -->
<!-- Start Right Section -->
<section class="special__accordion container d-lg-none d-block d-md-block">
    <h2 id="show-hide-accordion" class="main-title">
        حدد بحثك
        <i class="icon-s fas fa-search"></i>
    </h2>
    <div id="accordion" class="accordion">
    <form id="boxx" action="{{route('front_fodder_companies')}}" method="post">
        {{csrf_field()}}
        <div class="card">
            <div class="card-header" id="headingThree">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse"
                            data-target="#collapseThree"
                            aria-expanded="false" aria-controls="collapseThree">
                        <i class="far fa-calendar-alt"></i>
                        من
                    </button>
                </h5>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                 data-parent="#accordion">
                <div class="card-body">
                    <input class="date from w-100 text-center" type="date">
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h5 class="mb-0">
                    <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"
                            aria-expanded="false" aria-controls="collapseTwo">
                        <i class="far fa-calendar-alt"></i>
                        إلى
                    </button>
                </h5>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <input class="date to w-100 text-center" type="date">
                </div>
            </div>
        </div>
    </form>
    </div>
</section>
@include('../parts.alert')
<!-- End Right Section -->
<!-- Start Global Content -->
<article class="global__container container">
        <div class="row stats__details">
            <div class="col-12 ">
                <h1 class="title__page">{{ isset($_GET['country']) ?  $product->name .' - '. $_GET['country'] : $product->name  }}</h1>
            </div>
            <!-- Start Chart Part -->
            <section class="col-12 col-md-6 m-auto my-2 chart__container">
                <div id="chart4">

                </div>
            </section>
            <!-- Multi Select menu -->
     
            <!--    Table    -->
            <div class="col-12 p-0 statistics__main">
                <!-- Start Table -->
                <article class="table">
                    <section class="head">
{{--                    <h3 class="title display__flex">اسم الشركة</h3>--}}
                        <!-- Start Dropdown Item -->
                        <section class="title title__categories">
                            <select name="country" class="select__categories country">
                                <option selected value="">المنشأ</option>
                                @foreach($countries as $value)
                                    <option value="{{$value->country}}"{{ isset($_GET['country']) && $_GET['country'] == $value->country ? 'selected'  :'' }}>{{$value->country}}</option>

                                @endforeach
                            </select>
                        </section>
                        <h3 class="title display__flex">الكمية</h3>
                        <h3 class="title display__flex small__title">مقدار تغير الكمية عن المركب السابقة</h3>
                        <!-- End Dropdown Item -->
                    </section>

                    <div class="rows__container ">
                        <!-- Start One Row  -->
                        <div id="accordion">
                            @foreach($companies as $company)
                                <div class="card">
                                    <div class="card-header flex" id="headingOne{{$company->id}}" style="background-color: #fff;border-color: #1f6b00;margin: 0;padding: 10px;">
                                        <h5 class="mb-0" style="float: right;">
                                            <button class="btn btn-link" style="color: #1f6b00" data-toggle="collapse" data-target="#collapseOne{{$company->id}}" aria-expanded="true" aria-controls="collapseOne{{$company->id}}">
                                                <h5>{{$company->name}}</h5>
                                            </button>
                                        </h5>
                                        <span class="m-2" style="float: left;">
                                            <a style="color: #1f6b00" href="{{ route('front_company', $company->id) }}">  عن الشركة</a>
                                        </span>
                                    </div>

                                    <div id="collapseOne{{$company->id}}" class="collapse " aria-labelledby="headingOne{{$company->id}}" data-parent="#accordion">
                                        <div class="card-body">
                                            @foreach($ships as $value)
                                                @if($value->company && $value->company->id == $company->id)
                                                <section class="rows" style="min-height: 25px">
                                                    <div class="wall"></div>
                                                    <section class="data display__flex">
                                                        <h4 class="data__content">{{$value->country}}</h4>
                                                    </section>
                                                    <div class="wall"></div>
                                                    <section class="data display__flex">
                                                        <h4 class="data__content">{{$value->load}}</h4>
                                                    </section>
                                                    <div class="wall"></div>
                                                    <section class="data display__flex">

                                                        <h4 class="data__content up {{ $value->nums() < 0 ? 'text-danger' : '' }}">{{$value->nums()}}%</h4>
                                                    </section>
                                                </section>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <!-- End One Row  -->
                </article>
                <!-- End Table -->
            </div>
        </div>

    

</article>
<!-- End Global Content -->

@endsection



@section('script')



<script>
   



</script>
<script src="{{asset('Front_End/js/vendors/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script src="{{asset('Front_End/js/vendors/apexcharts.js')}}"></script>
<script>
    const options4 = {
        series: [
            <?php foreach($ships as  $value){ ?>

            <?php echo $value->load ?>,
        <?php } ?>
        ],
        chart: {
            width: 600,
            height:500,
            type: 'pie',
        },
        labels: [
            <?php foreach($ships as  $value){ ?>

            '<?php echo isset($value->company) ?   $value->Company->name : '' ?>',
        <?php } ?>
        ],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,

                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },
        legend: {
            show: true,
            showForSingleSeries: true,
            showForNullSeries: true,
            showForZeroSeries: true,
            position: 'right',
            horizontalAlign: 'center',
            fontSize: '16px',
            fontWeight: 600,
        }
    };
    const chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
    chart4.render();
</script>
<!-- End Scripts -->
<script>
    $('.select__categories').niceSelect();

    $(document).on('change','.to', function(){
        var from =$('.from').val();
        if (from !== '')
        {
            var link =  "?from=" +$('.from').val() + "&to=" +$(this).val() + "&country=" +$('.country').val()
            window.location.href = link
        }
    });

    $(document).on('change','.country', function(){
      
  
      var link = "?from=" +$('.from').val() + "&to=" +$('.to').val() + "&country=" +$(this).val()
      window.location.href = link

});
</script>


@endsection