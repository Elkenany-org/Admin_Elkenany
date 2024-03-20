
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/nice-select.css')}}">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="{{asset('Front_End/css/multi-select.css')}}">
<title> تفاصيل بورصة الاعلاف </title>
@endsection
@section('content')
<!-- Start Header -->

 <!-- Start breadcrumb -->
 <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
        <h2 class="page__title">  إحصائيات  بورصة الاعلاف - {{$member->StockFeed->name}}</h2>
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
<!-- Start Table -->
<div class="global__container container">
<section class="table__container table-5">
    <section class="table__head">
            <span class="title small__title">الشركة</span>
            <span class="title small__title">عدد التغير</span>
            <span class="title small__title">مقدار التغير اليومى</span>
            <span class="title small__title">مقدار التغير الاسبوعى</span>
            <span class="title small__title">مقدار التغير الشهرى</span>
    </section>
        <!-- Start One Row  -->
    <div class="table__body row__shadow">
        <section class="table__row row__image">

            <a href="{{ route('front_company',$member->Company->id) }}" class="cell__container">
                        <div class="cell__content__with__image">
                            <img class="image" src="{{asset('uploads/company/images/'.$member->Company->image)}}" alt="logo">
                            <span class="name"> {{$member->Company->name}}</span>
                        </div>
                </a>
           
                <div class="wall"></div>
            <section class="cell__container">
            <span class="cell__content up">{{$member->counts()}}</span>
            </section>
            <div class="wall"></div>
            <section class="cell__container">
                @if($member->days() > 0)
                <span class="cell__content up">  {{$member->days()}}%</span>
                @else
                <span class="cell__content down">  {{$member->days()}}%</span>
                @endif
            </section>
            <div class="wall"></div>
            <section class="cell__container">
                @if($member->week() > 0)
                <span class="cell__content up">  {{$member->week()}}%</span>
                @else
                <span class="cell__content down">  {{$member->week()}}%</span>
                @endif
            </section>
            <div class="wall"></div>
            <section class="cell__container">
                @if($member->oldprice() > 0)
                <span class="cell__content up">  {{$member->oldprice()}}%</span>
                @else
                <span class="cell__content down">  {{$member->oldprice()}}%</span>
                @endif
            </section>
        </section>
        <!-- End One Row  -->
    </div>
</section>
</div>

@endsection



@section('script')



<script>
   



</script>
<script src="{{asset('Front_End/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();

    $(document).on('change','.to', function(){
        var from =$('.from').val();
        if (from !== '')
        {
            var link = "{{url('fodder-stock-detials-member',$member->id)}}" + "?from=" +$('.from').val() + "&to=" +$(this).val()
            window.location.href = link
        }
    });

    $(document).on('change','.tom', function(){
        var from =$('.fromm').val();
        if (from !== '')
        {
            var link = "{{url('fodder-stock-detials-member',$member->id)}}" + "?from=" +$('.fromm').val() + "&to=" +$(this).val()
            window.location.href = link
        }
    });
</script>


@endsection