
@extends('layouts.front')
@section('style')
<link rel="stylesheet" href="{{asset('Front_End/css/ordinary_comparison.css')}}">
@endsection
@section('content')
<!-- Start Search Box -->
<article class="inner-search-box d-lg-block d-none">
    <div class="container holder">
        <form id="box" action="{{route('front_local_companies')}}" method="post" class="box-tabs">
        {{csrf_field()}}
            <section class="tabs">
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
                    <div id="checkboxes-companies" class="checkboxes">
                    @if(!empty($section->LocalStockMembers))
                        @foreach($section->LocalStockMembers as $key => $value)
                            @if($value->product_id == null)
                                <label for="company-one">
                                    <input class="checks  checks-company" value="{{$value->id}}" name="companies[]" type="checkbox" id="company-one"> {{$value->Company->name}}
                                </label>
                            @endif
                            @if($value->company_id == null)
                                <label for="company-one">
                                    <input class="checks  checks-company" value="{{$value->id}}" name="companies[]" type="checkbox" id="company-one"> {{$value->LocalStockproducts->name}}
                                </label>
                            @endif
                        @endforeach
                    @endif
                       
                    </div>
                </div>
                <!-- End MultisSelect Box -->
            </section>
            <section class="tabs tabs-especial sub">
                <span class="submit" id="search-box-submit">تطبيق</span>
            </section>
        </form>
    </div>
</article>
<!-- End Search Box -->

<!-- Start Comparison -->
<article class="comparison">
    <div class="container-fluid">
        <div class="holder cont" id="comparison-holder">

        </div>
    </div>
</article>
<!-- End Comparison -->

@endsection

@section('script')
<script src="{{asset('Front_End/js/ordinary_comparison .js')}}"></script>


<script type="text/javascript">
 
$(".sub").on('click',function(e){
  e.preventDefault();
  jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('front_local_companies')}}",
        type: 'post',
        data:  $('#box').serialize(),
        success : function(s,result){

        $('.cont').html('')
        $.each(s,function(k,v){
            $.each(v,function(e,r){
                console.log(r);
                $.each(r.local_stock_movement.slice(-1)[0].local_stock_detials,function(y,m){
                             
                                    if(r.product_id == null){
                                        if(m.local_stock_columns.type == 'price' ){
                                                    var price = m.value
                                        $('.cont').append(
                                            '<section class="company-card" style="margin-right:10px;">'+
                                                '<a class="company-head" href="{{ url("Guide-company") }}/'+r.company.id+'">'+
                                                    '<img class="company-logo" src="{{asset("uploads/company/images/")}}/'+r.company.image+'" alt="logo">'+
                                                    '<h3 class="company-title">'+r.company.name+'</h3>'+
                                                '</a>'+
                                                '<section class="item-box">'+
                                                '<h4 class="item-name">{{$section->name}}</h4>'+
                                                    '<h4 class="item-number">'+
                                                   
                                                    price  
                                                    
                                                    +'</h4>'+
                                                    '<h5 class="item-time">'+ r.local_stock_movement.slice(-1)[0].created_at +'</h5>'+
                                                '</section>'+
                                            '</section>'
                                            );
                                        } }else{
                                            if(m.local_stock_columns.type == 'price' ){
                                                    var price = m.value
                                            $('.cont').append(
                                                '<section class="company-card" style="margin-right:10px;">'+
                                                    '<a class="company-head">'+
                                                        '<img class="company-logo" src="{{asset("uploads/products/avatar/")}}/'+r.local_stockproducts.image+'" alt="logo">'+
                                                        '<h3 class="company-title">'+r.local_stockproducts.name+'</h3>'+
                                                    '</a>'+
                                                    '<section class="item-box">'+
                                                    '<h4 class="item-name">{{$section->name}}</h4>'+
                                                        '<h4 class="item-number">'+
                                                    
                                                        price  
                                                        
                                                        +'</h4>'+
                                                        '<h5 class="item-time">'+ r.local_stock_movement.slice(-1)[0].created_at +'</h5>'+
                                                    '</section>'+
                                                '</section>'
                                                );
                                            }
                                    }  
                      
                   
                })
            })

        })
         
        }
    });


});

</script>
@endsection