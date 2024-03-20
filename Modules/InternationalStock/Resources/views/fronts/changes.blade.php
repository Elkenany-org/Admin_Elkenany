
@extends('layouts.front')
@section('style')
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<title>  إحصائيات  حركة السفن </title>
@endsection
@section('content')

<!-- Start Header -->

 <!-- Start breadcrumb -->
 <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
        <h1 class="page__title">إحصائيات  حركة السفن</h1>
        </div>
    </div>

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
        <div class="row  no-gutters">   
            <!-- Start Chart Part -->
            <section class="col-12 col-md-6 m-auto my-2 chart__container">
                <div id="chart3">

                </div>
            </section>
            <!-- Multi Select menu -->
            <!-- Multi Select menu -->
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="multi__selectContainer">
                            <h4 class="multi__title">الأنواع</h4>
                            <select name="kind" class="select__categories selectCategories kind">
                            <option selected value="">الكل</option>
                            @foreach($products as $value)
                                <option value="{{$value->id}}" {{ $value->selected == 1 ? 'selected'  :'' }}>{{$value->name}}</option>

                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="multi__selectContainer">
                            <h4 class="multi__title">المنشأ</h4>
                            <select name="country" class="select__categories selectCategories country">
                                <option selected value="">الكل</option>
                                @foreach($countries as $value)
                                    <option value="{{$value->country}}"{{ $value->selected == 1 ? 'selected'  :'' }}>{{$value->country}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
            <!--    Table    -->
            <div class="col-12 p-0 statistics__main">
                <!-- Start Table -->
                <article class="table">
                    <section class="head">
                        <h3 class="title display__flex">النوع</h3>
                        <!-- Start Dropdown Item -->
                        <section class="title display__flex">
                            المنشأ
                        </section>
                        <h3 class="title display__flex">الكمية</h3>
                        <!-- End Dropdown Item -->
                    </section>
                    <div class="rows__container row__shadow">
                        @foreach($ships as $value)
                            <!-- Start One Row  -->
                            <section class="rows">
                                <section class="data display__flex">
                                    <h4 class="data__content">
                                        <a class="item__link" href="{{route('front_ships_statistc_kind',$value->ShipsProduct->id).'?country='.$value->country}}">{{$value->ShipsProduct->name}}</a>
                                    </h4>
                                </section>
                                <div class="wall"></div>
                                <section class="data display__flex">
                                    <h4 class="data__content">{{$value->country}}</h4>
                                </section>
                                <div class="wall"></div>
                                <section class="data display__flex">
                                    <h4 class="data__content">{{$value->ShipsProduct->Ships->sum('load')}}</h4>
                                </section>
                            </section>
                            <!-- End One Row  -->
                        @endforeach
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
      $('.select__categories').niceSelect();
    $(document).ready(function () {
        $('#multiple-checkboxes').multiselect({
            includeSelectAllOption: true,
        });
    });
    const options3 = {
        series: [
            <?php foreach($products as  $value){ ?>
            
                <?php echo $value->Ships->sum('load') ?>, 
            <?php } ?>
            ],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: [
            <?php foreach($products as  $value){ ?>
            
                '<?php echo $value->name ?>', 
            <?php } ?>
        ],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
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
            fontSize: '20px',
            fontWeight: 600,
        }
    };
    const chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
    chart3.render();

    const options4 = {
        series: [7, 10, 12, 17],
        labels: ['TEAM A', 'TEAM B', 'TEAM C', 'TEAM D'],
        chart: {
            width: 380,
            type: 'pie',
        },
        stroke: {
            colors: ['#fff']
        },
        fill: {
            opacity: 0.8
        },
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
        dataLabels: {
            enabled: true
        },
    };
    const chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
    chart4.render();

    const options5 = {
        series: [44, 55, 13, 43, 22],
        chart: {
            width: 380,
            type: 'pie',
        },
        labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }],
    };
    const chart5 = new ApexCharts(document.querySelector("#chart5"), options5);
    chart5.render();
      setTimeout(
          function()
          {
              //mouseleave
              $(document).on('mouseleave','.to', function(){
                  var from =$('.from').val();
                  var to =$('.to').val();
                  if (from !== '' && to != '')
                  {
                      var link = "{{url('all-ships-traffic-statistc')}}" + "?from=" +$('.from').val() + "&to=" +$(this).val() + "&kind=" +$('.kind').val() + "&country=" +$('.country').val()
                      window.location.href = link
                  }
              });
          }, 8000);


    $(document).on('change','.kind', function(){
      
  
            var link = "{{url('all-ships-traffic-statistc')}}" + "?from=" +$('.from').val() + "&to=" +$('.to').val() + "&kind=" +$(this).val() + "&country=" +$('.country').val()
            window.location.href = link

    });

    $(document).on('change','.country', function(){
      
  
      var link = "{{url('all-ships-traffic-statistc')}}" + "?from=" +$('.from').val() + "&to=" +$('.to').val() + "&kind=" +$('.kind').val() + "&country=" +$(this).val()
      window.location.href = link

});
</script>


@endsection