
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{asset('Front_End/css/multi-select.css')}}">
<script>
<title>     إحصائيات البورصات اليومية </title>
 // Replace Math.random() with a pseudo-random number generator to get reproducible results in e2e tests
    // Based on https://gist.github.com/blixt/f17b47c62508be59987b
    var _seed = 42;
    Math.random = function () {
        _seed = _seed * 16807 % 2147483647;
        return (_seed - 1) / 2147483646;
    };

    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.min.js"><\/script>'
    )
    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/eligrey-classlist-js-polyfill@1.2.20171210/classList.min.js"><\/script>'
    )
    window.Promise ||
    document.write(
        '<script src="https://cdn.jsdelivr.net/npm/findindex_polyfill_mdn"><\/script>'
    )
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection
@section('content')

<!-- Start Header -->

 <!-- Start breadcrumb -->
 
  <!-- Start breadcrumb -->
  <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
        <h1 class="page__title">إحصائيات البورصة اليومية</h1>
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
                <input class="from date" name="from" value="{{$from}}" type="date">
            </section>
            <section class="tabs tabs-2">
                <i class="far fa-calendar-alt"></i>
                <h4 class="title">إلى:</h4>
                <input class="to date" name="to" value="{{$to}}" type="date">
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
            <div id="collapseThree" class="collapse show" aria-labelledby="headingThree"
                 data-parent="#accordion">
                <div class="card-body">
                <input class="fromm date" name="from" value="{{$from}}" type="date">
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
            <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                <input class="tom date" name="to" value="{{$to}}" type="date">
                </div>
            </div>
        </div>
    </div>
</section>
@include('../parts.alert')
<!-- End Right Section -->
<!-- Start Global Content -->
<article class="global-content">
    <div class="container">
        <div class="row">
            <!-- Start Chart Part -->
            <section class="col-12 chart__container">
                <div id="chart">
                    <div class="toolbar">
                        <button id="one_month">1M</button>
                        <button id="six_months">6M</button>
                        <button id="one_year" class="active">1Y</button>
                        <button id="all">ALL</button>
                    </div>
                    <div id="chart-timeline"></div>
                     <script>
                       
                           
                            var options = {
                                
                                series: [
                                <?php foreach($sections as  $sec){ ?>
                                {
                                    name: '<?php echo $sec->name ?>',
                                    data: [
                                        <?php foreach($sec->LocalStockCounts as  $value){ ?>
                                          
                                        [new Date('<?php echo $value->created_at ?>').getTime(), <?php echo $value->change ?>],
                                            
                                        <?php } ?>
                                    ]
                               
                                   
                                },
                                <?php } ?>
                                ],
                                chart: {
                                    id: 'area-datetime',
                                    type: 'area',
                                    height: 350,
                                    width: '100%',
                                    zoom: {
                                        autoScaleYaxis: true
                                    },

                                },
                                
                                annotations: {
                                    yaxis: [{
                                        y: 30,
                                        borderColor: '#999',
                                        label: {
                                            show: false,
                                            text: '',
                                            style: {
                                                color: "#fff",
                                                background: '#00E396'
                                            },
                                        }
                                    }],
                                    xaxis: [{
                                        x: new Date('01 Jan 2021').getTime(),
                                        borderColor: '#999',
                                        yAxisIndex: 0,
                                        label: {
                                            show: false,
                                            text: '',
                                            style: {
                                                color: "#fff",
                                                background: '#775DD0'
                                            }
                                        }
                                    }]
                                },
                                dataLabels: {
                                    enabled: false
                                },
                                markers: {
                                    size: 0,
                                    style: 'hollow',
                                },
                                xaxis: {
                                    type: 'datetime',
                                    min: new Date('01 Jan 2021').getTime(),
                                    tickAmount: 6,
                                },
                                tooltip: {
                                    x: {
                                        format: 'dd MMM yyyy'
                                    }
                                },
                                fill: {
                                    type: 'gradient',
                                    gradient: {
                                        shadeIntensity: 1,
                                        opacityFrom: 0.7,
                                        opacityTo: 0.9,
                                        stops: [0, 100]
                                    }
                                },
                            };

                    var chart = new ApexCharts(document.querySelector("#chart-timeline"), options);
                    chart.render();

                    var resetCssClasses = function (activeEl) {
                        var els = document.querySelectorAll('button')
                        Array.prototype.forEach.call(els, function (el) {
                            el.classList.remove('active')
                        })

                        activeEl.target.classList.add('active')
                    }
                    document
                        .querySelector('#one_month')
                        .addEventListener('click', function (e) {
                            resetCssClasses(e)

                            chart.zoomX(
                                new Date(new Date().getTime() - parseInt(30)).getTime(),
                                new Date().getTime()
                                
                            )
                        })

                    document
                        .querySelector('#six_months')
                        .addEventListener('click', function (e) {
                            resetCssClasses(e)

                            chart.zoomX(
                                new Date(new Date().getTime() - parseInt(30*6)).getTime(),
                                new Date().getTime()

                                
                            )
                        })

                    document
                        .querySelector('#one_year')
                        .addEventListener('click', function (e) {
                            resetCssClasses(e)
                            chart.zoomX(
                                new Date(new Date().getTime() - parseInt(30*12)).getTime(),
                                new Date().getTime()
                                
                            )
                        })

                    document.querySelector('#all').addEventListener('click', function (e) {
                        resetCssClasses(e)

                        chart.zoomX(
                            new Date('01 Jan 2021').getTime(),
                            new Date().getTime()
                        )
                    })





                    </script>
                </div>
            </section>
            <!-- Multi Select menu -->
            <!-- Multi Select menu -->
            <div class="col-12">
                <div class="multi__selectContainer">
                    <h4 class="multi__title">البورصات</h4>
                    <form id="box" action="{{route('get_local_statistic')}}" method="post" class="box-tabs">
                        {{csrf_field()}}
                        <input type="hidden" name="section" value="{{$section->id}}">
                        <select id="multiple-checkboxes" class='local' name="local[]" multiple="multiple">
                            @foreach($sections as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach  
        
                        </select>
                    </form>
                </div>
            </div>
            <!--    Table    -->
            <section class="col-12">
                <div class="table__main">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col" class="border-right border-left">اسم البورصة</th>
                            <th scope="col" class="border-right">مقدار التغير</th>
                            <th scope="col" class="border-right">عدد التغير</th>
                        </tr>
                        </thead>
                        <tbody class="bad">
                        @foreach($sections as $value)
                            <tr>
                                <th scope="row">{{$value->id}}</th>
                                <td class="border-right border-left company__link__container">
                                    <a href="{{ route('front_local_detials',$value->id) }}" class="company__link">{{$value->name}}</a>
                                </td>
                                <td class="border-right"><span class="negative__content"> {{$value->laust()}}% </span></td>
                                <td class="border-right"><span class="negative__content"> {{$value->counts()}} </span></td>
                            </tr>
                        @endforeach  
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

    </div>
    {{csrf_field()}}
</article>
<!-- End Global Content -->

@endsection



@section('script')

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script src="{{asset('Front_End/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
$(document).ready(function () {
        $('#multiple-checkboxes').multiselect({
            includeSelectAllOption: true,
        });
    });

    $(document).on('change','.to', function(){
        var from =$('.from').val();
        if (from !== '')
        {
            var link = "{{url('local-stock-statistics',$section->id)}}" + "?from=" +$('.from').val() + "&to=" +$(this).val()
            window.location.href = link
        }
    });

    $(document).on('change','.tom', function(){
        var from =$('.fromm').val();
        if (from !== '')
        {
            var link = "{{url('local-stock-statistics',$section->id)}}" + "?from=" +$('.fromm').val() + "&to=" +$(this).val()
            window.location.href = link
        }
    });

$(".local").on('change',function(e){


$('#box').submit();
  

});
</script>
@endsection