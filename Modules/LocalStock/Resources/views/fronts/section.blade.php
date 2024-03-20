
@extends('layouts.front')
@section('style')
<script src="{{asset('Front_End/js/vendors/apexcharts.js')}}"></script>
<title>      {{$section->name}} </title>
<style type="text/css">

img.image {
    width: 75px;
    height: 75px;
    border-radius: 0.375rem;
}
</style>
@endsection
@section('content')

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
                    <a href="{{$logo->link}}" target="_blank" class="logo-holder"><img alt="partner logo" src="{{asset('uploads/full_images/'.$logo->image)}}"></a>
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
            <h1 class="page__title">{{$section->name}}</h1>
        </div>
    </div>
    <!-- End breadcrumb -->
  <!-- Start Search Box -->
  <article class="inner-search-box d-lg-block d-none">
        <div class="container">
            <form class="box-tabs">
                <div class="search-box-overlay"></div>
                <section class="tabs tabs-4">
                <i class="fas fa-puzzle-piece"></i>
                    <h4 class="title">القطاع:</h4>
                    <select class="form-control" onChange="window.location.href=this.value">
                        @foreach($secs as $value)
                            @if(isset($_GET['type']))
                                <option value="{{ route('front_local_sections',$value->type) }}" {{ $_GET['type'] == $value->type ? 'selected'  :'' }}>{{$value->name}}</option>
                            @else
                                <option value="{{ route('front_local_sections',$value->type) }}"
                                        {{ isset($section) && $section->section_id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                            @endif

                        @endforeach  
                    </select>
                </section>
                <section class="tabs  tabs-4">
                <i class="fas fa-list-ul"></i>
                    <h4 class="title">البورصة:</h4>
                    <select class="form-control" onChange="window.location.href=this.value">
                        @foreach($subs as $value)
                        <option value="{{ $value->section != "" ? route('front_local_members',$value->id) .'/'.$value->section : route('front_local_members',$value->id) }}"
                        {{ isset($section) && $section->id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
                        @endforeach  
                    </select>
                </section>
               
                <section class="tabs  tabs-4">
                    <i class="far fa-calendar-alt"></i>
                    <h4 class="title">التاريخ:</h4>
                    <input class="date slec" value="{{isset($_GET['date']) ? $_GET['date'] : ''}}" name="date" type="date">
                </section>
                <section class="tabs  tabs-4">
                    <i class="fas fa-poll"></i>
                    <h4 class="title">إحصائيات:</h4>
                    <a href="{{ route('front_local_statistic_members',$section->id) }}" class="stats__btn">إحصائيات</a>
                </section>
            </form>
        </div>
    </article>
    <!-- End Search Box -->
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
                                    <input onChange="window.location.href=this.value" id="tab-1-val-{{$value->id}}" name="tab-1-values" type="radio" value="{{ route('front_local_sections',$value->type) }}">
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
                                        <input  onChange="window.location.href=this.value" id="tab-6-val-{{$value->id}}" name="tab-6-values" type="radio" value="{{ route('front_local_members',$value->id) }}"
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
                    <input class="date slec" value="{{isset($_GET['date']) ? $_GET['date'] : ''}}" name="date" type="date">
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
                        <a class="stats__btn" href="{{ route('front_local_statistic_members',$section->id) }}">إحصائيات</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('../parts.alert')
    <!-- End Accordion Section -->

<div class="global__container">
    <section class="col-md-12 text-center container st-1" style="margin-bottom: 10px;border-radius: 1px 1px 20px 20px;background-color: #FFAA00;height:55px;display: {{$status != 'new' ? '' : 'none'}}; " >
        <div class="custom__breadcrumb" style="line-height: 55px">
            <h6 class="page__title" style="line-height: 55px;"><strong>تنوية: تم اخر تحديث في يوم  {{$date}}</strong></h6>
        </div>
    </section>
    <!-- Start Table 7 cols with chart -->
    <section class="table__container table-chart-7 special__container">
        <section class="table__head">
        <span class="title">الإسم</span>
                @if(!empty($section->LocalStockColumns))
                    @foreach($section->LocalStockColumns as $column)
                        @if($column->type == null )
                            <span class="title"> {{ $column->name }}</span>
                        @endif
                        @if($column->type == 'price' )
                            <span class="title"> {{ $column->name }}</span>
                        @endif
                        @if($column->type == 'change' )
                            <span class="title"> {{ $column->name }}</span>
                        @endif
                    
                    @endforeach
                    <span class="title title__chart  d-lg-flex d-none">إتجاه السعر</span>
                @endif
        </section>
        <!--  add row__shadow when rows length > = 3  -->
        <div class="table__body row__shadow">

        @if(!empty($movessort))
                @foreach($movessort as $key => $value) 
                <!-- Start One Row  -->
                <section class="table__row row__image ads">
                            @if($value->LocalStockMember->product_id == null)
                                <a href="{{ route('front_company',$value->LocalStockMember->Company->id) }}" class="cell__container">
                                <div class="cell__content__with__image">
                                    <img class="image" src="{{asset('uploads/company/images/'.$value->LocalStockMember->Company->image)}}" alt="logo">
                                    <span class="name"> {{$value->LocalStockMember->Company->name}}</span>
                                </div>
                                </a>
                            @endif
                            @if($value->LocalStockMember->company_id == null)
                                <section class="cell__container">
                                @if($value->LocalStockMember->LocalStockproducts->image != null)
                                    <img class="image" src="{{asset('uploads/products/avatar/'.$value->LocalStockMember->LocalStockproducts->image)}}" alt="logo">
                                @endif 
                                    <span class="cell__content name"> {{$value->LocalStockMember->LocalStockproducts->name}}</span>
                                </section>
                            @endif

                            @foreach($value->LocalStockDetials as $details_key => $Mco)
                                @if($Mco->LocalStockColumns->type == null )
                                    <div class="wall"></div>
                                    <section class="cell__container">
                                        <span class="cell__content">{{ $Mco->value }}</span>
                                    </section>
                                    
                                @endif
                                @if($Mco->LocalStockColumns->type == 'price' )
                                    <div class="wall"></div>
                                    <section class=" cell__container">
                                        <span class="cell__content">{{$Mco->value}} جنية</span>
                                    </section>
                                @endif
                                @if($Mco->LocalStockColumns->type == 'change' )
                                    <div class="wall"></div>
                                    <section class="cell__container">
                                    @if($Mco->value < '0' )
                                        <span class="cell__content down">{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    @if($Mco->value > '0' )
                                        <span class="cell__content up">+{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    @if($Mco->value === '0' )
                                        <span class="cell__content">{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    </section>
                                @endif
                                
                            
                            @endforeach
                            <div class="wall  d-lg-block d-none"></div>
                            <section class="cell__container data__charts d-lg-block d-none">
                                <div class="chart{{$value->id}}"></div>
                            
                                <script>
                                    var changes = [];
                                    var dates = [];

                                    <?php foreach($value->LocalStockMember->movements()['changes'] as  $ch){ ?>
                                        changes.push('<?php echo $ch; ?>');
                                    <?php } ?>

                                    <?php foreach($value->LocalStockMember->movements()['dates'] as $da){ ?>
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
                </section>
                <!-- End One Row  -->
                @endforeach
            @endif

        @if(!empty($moves))
                @foreach($moves as $key => $value)
                <!-- Start One Row  -->
                <section class="table__row row__image">
                            @if($value->LocalStockMember->product_id == null)
                                <a  class="cell__container" href="{{ route('front_company',$value->LocalStockMember->Company->id) }}">
                                <div class="cell__content__with__image">
                                    <img class="image" src="{{asset('uploads/company/images/'.$value->LocalStockMember->Company->image)}}" alt="logo">
                                    <span class="name"> {{$value->LocalStockMember->Company->name}}</span>
                                </div>
                                </a>
                            @endif
                            @if($value->LocalStockMember->company_id == null)
                                <section class="cell__container">
                                @if($value->LocalStockMember->LocalStockproducts->image != null)
                                    <img class="image" src="{{asset('uploads/products/avatar/'.$value->LocalStockMember->LocalStockproducts->image)}}" alt="logo">
                                @endif 
                                    <span class="cell__content name"> {{$value->LocalStockMember->LocalStockproducts->name}}</span>
                                </section>
                            @endif

                            @foreach($value->LocalStockDetials as $details_key => $Mco)
                                @if($Mco->LocalStockColumns->type == null )
                                    <div class="wall"></div>
                                    <section class="cell__container">
                                        <span class="cell__content">{{ $Mco->value }}</span>
                                    </section>
                                    
                                @endif
                                @if($Mco->LocalStockColumns->type == 'price' )
                                    <div class="wall"></div>
                                    <section class=" cell__container">
                                        <span class="cell__content">{{$Mco->value}} جنية</span>
                                    </section>
                                @endif
                                @if($Mco->LocalStockColumns->type == 'change' )
                                    <div class="wall"></div>
                                    <section class="cell__container">
                                    @if($Mco->value < '0' )
                                        <span class="cell__content down">{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    @if($Mco->value > '0' )
                                        <span class="cell__content up">+{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    @if($Mco->value === '0' || $Mco->value === '0.00')
                                        <span class="cell__content">{{round($Mco->value, 2)}}</span>
                                        <span class="cell__content time">{{Date::parse($value->created_at)->format('H:i / Y-m-d')}}</span>
                                    @endif
                                    </section>
                                @endif
                                
                            
                            @endforeach
                            <div class="wall  d-lg-block d-none"></div>
                            <section class="cell__container data__charts d-lg-block d-none">
                                <div class="chart{{$value->id}}"></div>
                            
                                <script>
                                    var changes = [];
                                    var dates = [];

                                    <?php foreach($value->LocalStockMember->movements()['changes'] as  $ch){ ?>
                                        changes.push('<?php echo $ch; ?>');
                                    <?php } ?>

                                    <?php foreach($value->LocalStockMember->movements()['dates'] as $da){ ?>
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
                </section>
                <!-- End One Row  -->
                @endforeach
            @endif
            <!-- End One Row  -->
        </div>
    </section>
    <!-- End Table -->


    <!--   ///////////////////////////////////////////////////////////////////////////////////////////////////     -->


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

 
@endsection

@section('script')
<script src="{{asset('Front_End/js/vendors/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>
<script>
$(document).on('change','.date', function(){
    var link =  "?date=" +$(this).val();

    window.location.href = link

});
</script>
@endsection