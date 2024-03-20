
@extends('layouts.front')
@section('style')
<title>     مقارنة {{$section->name}} </title>
@endsection
@section('content')

<!-- Start Header -->

    <div class="breadcrumb__container container">
        <div class="custom__breadcrumb">
        <h1 class="page__title">مقارنة {{$section->name}}</h1>
        </div>
    </div>
    <!-- End breadcrumb -->
    <!-- Start Search Box -->
    <article class="inner-search-box d-lg-block d-none d-sm-none d-md-none">
        <div class="container">
            <form id="box" action="{{route('front_fodder_companies')}}" method="post" class="box-tabs">
            {{csrf_field()}}
            <div class="search-box-overlay"></div>
                <section class="tabs tabs-3">
                    <i class="far fa-building"></i>
                    <h4 class="title">الشركات:</h4>
                    <!-- Start MultisSelect Box -->
                    <div class="multiselect" id="multiselect-companies">
                        <div class="selectBox" onclick="showCheckboxes('companies[]')">
                            <select>
                                <option>اختر الشركات</option>
                            </select>
                            <span class="warning">حد أقصي 3 شركات</span>
                            <span class="companies-error">اختر شركة واحد علي الاقل</span>
                            <div class="overSelect"></div>
                        </div>
                        <div class="checkboxes" id="checkboxes-companies">
                            <div class="company__search">
                                <label for="search">
                                    <input class="" id="search" name="search" placeholder="ابحث عن"
                                           type="search">
                                </label>
                            </div>
                        @if(!empty($sections))
                            @foreach($sections as $key => $value)
                            <label class="company__filter__label" for="company-{{$value->Company->id}}">
                                    <input class="checks  checks-company" value="{{$value->Company->id}}" name="companies[]" type="checkbox" id="company-{{$value->Company->id}}"> {{$value->Company->name}}
                                </label>

                            @endforeach
                        @endif
                        </div>
                    </div>
                    <!-- End MultisSelect Box -->
                </section>
                <section class="tabs tabs-3">
                    <i class="fas fa-bars"></i>
                    <h4 class="title">الأصناف:</h4>
                    <!-- Start MultisSelect Box -->
                    <div class="multiselect" id="multiselect-items">
                        <div class="selectBox" onclick="showCheckboxes('items[]')">
                            <select>
                                <option>اختر الأصناف</option>
                            </select>
                            <span class="items-counter" id="items-counter">عدد الأصناف: 0</span>
                            <span class="items-error">اختر صنف واحد علي الاقل</span>
                            <div class="overSelect"></div>
                        </div>
                        <div class="checkboxes" id="checkboxes-items">
                            <div class="type__search">
                                <label for="search__type">
                                    <input class="search__inputs" id="search__type" name="search" placeholder="ابحث عن"
                                           type="search">
                                </label>
                            </div>
                        @if(!empty($section->StockFeeds))
                            @foreach($section->StockFeeds as $key => $value)
                            <label class="type__filter__label" for="item-{{$value->id}}">
                                    <input class="checks checks-item" value="{{$value->id}}" name="items[]" type="checkbox" id="item-{{$value->id}}"> {{$value->name}}
                                </label>
                            @endforeach
                        @endif
                        </div>
                    </div>
                    <!-- End MultisSelect Box -->
                </section>
                <section class="tabs tabs-3 tabs-especial sub" onClick="submit()">
                    <span class="submit" id="search-box-submit">تطبيق</span>
                </section>
            </form>
        </div>
    </article>
    <!-- End Search Box -->

    <section class="special__accordion container d-lg-none d-block d-md-block" id="fodder__comparison">
        <button aria-controls="collapseExample" aria-expanded="false" class="main-title" data-toggle="collapse"
                href="#accordion"
                id="show-hide-accordion" role="button">
            تحديد الشركات
            <i class="icon-s fas fa-search"></i>
        </button>
        <div class="accordion collapse show" id="accordion">
        <form id="box" action="{{route('front_fodder_companies')}}" method="post" class="box-tabs">
            {{csrf_field()}}
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button aria-controls="collapseOne" aria-expanded="true" class="btn"
                                data-target="#collapseOne" data-toggle="collapse"  type="button">
                            <i class="far fa-building"></i>
                            الشركات
                        </button>
                        <span class="companies-error-accordion">اختر شركة واحد علي الاقل</span>
                    </h5>
                </div>
                <div aria-labelledby="headingOne" class="collapse show" data-parent="#accordion" id="collapseOne">
                    <div class="card-body">
                        <!-- Start MultisSelect Box -->
                        <div class="multiselect multiselect-accordion" id="multiselect-companies-accordion">
                            <div class="selectBox" onclick="showCheckboxes('companies[]')">
                                <span class="warning">حد أقصي 3 شركات</span>
                                <div class="overSelect"></div>
                            </div>
                            <!--        ADD list__shadow IF list length >5                    -->
                            <div class="checkboxes list__shadow" id="checkboxes-companies-accordion">
                                <div class="company__search">
                                    <label for="search">
                                        <input class="" id="search__accordion" name="search" placeholder="ابحث عن"
                                               type="search">
                                    </label>
                                </div>
                             
                            
                                    @if(!empty($sections))
                            @foreach($sections as $key => $value)
                            <label class="company__filter__label-accordion" for="company-{{$value->Company->id}}">
                                    <input class="checks  checks-company-accordion" value="{{$value->Company->id}}" name="companies[]" type="checkbox" id="company-{{$value->Company->id}}"> {{$value->Company->name}}
                                </label>

                            @endforeach
                        @endif
                            </div>
                        </div>
                        <!-- End MultisSelect Box -->
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button aria-controls="collapseTwo" aria-expanded="true"  type="button" class="btn collapsed"
                                data-target="#collapseTwo" data-toggle="collapse">
                            <i class="fas fa-bars"></i>
                            الأصناف
                        </button>
                        <span class="items-error-accordion">اختر صنف واحد علي الاقل</span>
                    </h5>
                </div>
                <div aria-labelledby="headingTwo" class="collapse show" data-parent="#accordion"  id="collapseTwo">
                    <div class="card-body">
                        <!-- Start MultisSelect Box -->
                        <div class="multiselect multiselect-accordion" id="multiselect-items-accordion">
                            <div class="selectBox" onclick="showCheckboxes('items[]')">
                                <span class="items-counter" id="items-counter-accordion">عدد الأصناف: 0</span>
                                <div class="overSelect"></div>
                            </div>
                            <!--        ADD list__shadow IF list length >5                    -->
                            <div class="checkboxes list__shadow" id="checkboxes-items-accordion">
                                <div class="type__search">
                                    <label for="search__type">
                                        <input class="search__inputs" id="search__type__accordion" name="search"
                                               placeholder="ابحث عن"
                                               type="search">
                                    </label>
                                </div>
                             
                                @if(!empty($section->StockFeeds))
                            @foreach($section->StockFeeds as $key => $value)
                            <label class="type__filter__label-accordion" for="item-{{$value->id}}">
                                    <input class="checks checks-item-accordion" value="{{$value->id}}" name="items[]" type="checkbox" id="item-{{$value->id}}"> {{$value->name}}
                                </label>
                            @endforeach
                        @endif
                            </div>
                        </div>
                        <!-- End MultisSelect Box -->
                    </div>
                </div>
            </div>
            <a class="submit-accordion sub"  onClick="submit()">تطبيق</a>
            </form>
        </div>
    </section>

<!-- Start Comparison -->
<article class="comparison container">
    <div class="comparison-holder cont" id="comparison-holder">
    </div>
</article>
<!-- End Comparison -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/fodder_comparison.js')}}"></script>


<script type="text/javascript">


$(".sub").on('click',function(e){
  e.preventDefault();
    $.ajax(
    {
        url: "{{url('get-fodder-company')}}",
        type: 'post',
        data:  $('#box').serialize(),
        success : function(s,result){
            console.log(s);
            $('.cont').html('')
            $.each(s.comp,function(k,r){

                $('.cont').append(
                    '<section class="center company-card company-card'+r.id+'">'+
                        '<a class="company-head" href="{{ url("Guide-company") }}/'+r.id+'">'+
                            '<img class="company-logo" src="{{asset("uploads/company/images/")}}/'+r.image+'" alt="logo">'+
                            '<h3 class="company-title">'+r.name+'</h3>'+
                        '</a>'+
                      
                    '</section>'
                    );
                   
            });

            $.each(s.datas,function(k,t){

            $('.company-card'+t.company_id).append(
                
                        '<section class="item-box item'+t.stock_feed.id+'">'+
                        '<h4 class="item-name"> '+t.stock_feed.name+'</h4>'+
                            '<h4 class="item-number">'+
    
                            t.fodder_stock_moves.slice(-1)[0].price  

                            +'</h4>'+
                            '<h5 class="item-time">'+ t.fodder_stock_moves.slice(-1)[0].created_at +'</h5>'+
                        '</section>'
                );
            
            })

            var feeds = <?php print_r(json_encode($section->StockFeeds)) ?>;

$.each(feeds,function(k,u){
    

$(".item"+u.id).mouseenter(function() {
    $(".item"+u.id).addClass( "item__box__hover" );
  }).mouseleave(function() {
    $(".item"+u.id).removeClass( "item__box__hover" );
  });

});
 
          
         
        }
    });


});


</script>
@endsection