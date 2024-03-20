@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 150px;
	}
	#avatar:hover{
		width: 150px;
		cursor: pointer;
	}
</style>
@endsection

@section('content')
<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
        <h6 class="m-0" style="display: inline;">منتاجات وشركات <span class="text-primary"> {{$section->name}} </span></h6>
        <a href="{{route('addMember',$section->id)}}" class="btn btn-primary" style="float: left;">
              إضافة منتجات لبورصة 
              <i class="fas fa-plus"></i>
        </a>
        <button  style="float: left;margin-left:10px;height:38px;" class="btn btn-success btn-sm alll" type="submit">  تحديث الكل</button>
    </div>

    <div class="card-body">
      <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            {{-- product --}}
            <li class="nav-item">
              <a class="nav-link active" id="custom-content-below-product-tab" data-toggle="pill" href="#custom-content-below-product" role="tab" aria-controls="custom-content-below-product" aria-selected="true">المنتجات</a>
            </li>
            {{-- company --}}
            <li class="nav-item">
              <a class="nav-link" id="custom-content-below-company-tab" data-toggle="pill" href="#custom-content-below-company" role="tab" aria-controls="custom-content-below-company" aria-selected="false">الشركات</a>
            </li>
      </ul>
    </div>

    <div class="tab-content" id="custom-content-below-tabContent">
      {{-- product --}}
      <div class="tab-pane fade show active"  id="custom-content-below-product" role="tabpanel" aria-labelledby="custom-content-below-product-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="card-header">
              <h6 class="m-0" style="display: inline;">منتجات<span class="text-primary"> {{$section->name}} </span></h6>

            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                  <th>#</th>
                  <th>اسم المنتج</th>
                    @if(!empty($section->LocalStockColumns))
                      @foreach($section->LocalStockColumns as $column)
                        <th>
                          {{ $column->name }}
                        </th>
                      @endforeach
                    @endif
                  <th>التحديث</th>
                  <th>الحذف</th>
                  </tr>
                </thead>
                <tbody>
                @if(!empty($section->LocalStockMembers))
                  @foreach($section->LocalStockMembers as $key => $value)
                    @if($value->company_id == null)
                      <tr>
                        <td>{{$key+1}}</td>
                        @if($value->status == 1)
                            <td class="nam"><p class="nem" style="color:green;font-weight:bold">{{$value->LocalStockproducts->name}}</p></td>
                        
                        @endif     
                        @if($value->status == 0 )
                          <td class="nam"><p class="nem" style="color:red;font-weight:bold">{{$value->LocalStockproducts->name}}</p></td>
                        @endif
                    @if(!empty($value->LastMovement()->LocalStockDetials))
                    <form action="{{route('updateMember')}}" method="post" id="addform{{$value->id}}" style="display: inline-block;">
                    @foreach($value->LastMovement()->LocalStockDetials as $Mco)
                        {{csrf_field()}}
                        @if($Mco->LocalStockColumns->type == 'price' )
                          <td>
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="hidden" name="section" value="{{$value->section_id}}">
                            <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                            <input type="number" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == 'change' )
                          <td class="change">
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="hidden" name="section" value="{{$value->section_id}}">
                            <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                            <input type="hidden" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                            <span class="spa">{{ $Mco->value }}</span>
                            
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == 'state' )
                          <td class="state">
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="hidden" name="section" value="{{$value->section_id}}">
                            <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                            <input type="hidden" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                            
                            @if($Mco->value === 'up' )
                              <img class="image" src="https://admin.elkenany.com/uploads/full_images/arrows3-01.png" style="width:20px">
                            @endif
                            @if($Mco->value === 'down' )
                              <img class="image" src="https://admin.elkenany.com/uploads/full_images/arrows3-02.png" style="width:20px">
                            @endif
                            @if($Mco->value === 'equal' )
                              <img class="image" src="https://admin.elkenany.com/uploads/full_images/arrows3-03.png" style="width:20px">
                            @endif
                        
                          </td>
                        @endif
                        @if($Mco->LocalStockColumns->type == null )
                          <td>
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <input type="hidden" name="section" value="{{$value->section_id}}">
                            <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                            <input type="text" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                          </td>
                        @endif
                      @endforeach
                      @endif
                      <td style="display:inline-flex">
                      <button id="submitForm" style="display:inline;border-bottom-left-radius: 0px;border-top-left-radius: 0px;" data-section= "{{$value->section_id}}" data-member = "{{$value->id}}" class="btn btn-primary btn-sm update_col" type="submit">  تحديث</button>
                      <div class="dropdown" style="display:inline">
                        <button  style="display:inline;width:30px;padding:5px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <a href="{{route('showmovements',$value->id)}}" style="width:100%;text-align:center" class="dropdown-item " type="submit"> التحديثات</a>
                          @if($value->status == 1)
                          <button data-member = "{{$value->id}}" style="width:100%" class="btn btn-light warning" type="button">عدم تحديث</button>
                          @endif

                        </div>
                      
                      </div>
                      </td>
                    </form>
                      <td>
                          <form action="{{route('Deletemember')}}" method="post" style="display: inline-block;">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                          </form>
                      </td>
                      </tr>
                      @endif
                  @endforeach
                  @endif
                </tbody>
              </table>
              </div>
          </div>     
        </div>
      </div>
      
      {{-- company --}}
      <div class="tab-pane fade" id="custom-content-below-company" role="tabpanel" aria-labelledby="custom-content-below-company-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="card-header">
              <h6 class="m-0" style="display: inline;">شركات<span class="text-primary"> {{$section->name}} </span></h6>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>اسم الشركة</th>
                      @if(!empty($section->LocalStockColumns))
                      @foreach($section->LocalStockColumns as $column)
                      <th>
                        {{ $column->name }}
                      </th>
                      @endforeach
                      @endif
                      <th>التحديث</th>
                      <th>الحذف</th>
                    </tr>
                  </thead>
                  <tbody>
                  @if(!empty($section->LocalStockMembers))
                  @foreach($section->LocalStockMembers as $key => $value)
                      @if($value->product_id == null)
                        <tr>
                          <td>{{$key+1}}</td>
                          @if($value->status == 1)
                              <td class="nam"><p class="nem" style="color:green;font-weight:bold">{{$value->Company->name}}</p></td>
                          @endif  
                          @if($value->status == 0 )
                            <td class="nam"><p class="nem" style="color:red;font-weight:bold">{{$value->Company->name}}</p></td>
                          @endif
                          @if(!empty($value->LastMovement()->LocalStockDetials))
                          <form action="{{route('updateMember')}}" method="post" id="addform{{$value->id}}" style="display: inline-block;">
                          @foreach($value->LastMovement()->LocalStockDetials as $details_key => $Mco)
                              {{csrf_field()}}
                              @if($Mco->LocalStockColumns->type == 'price' )
                            <td>
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <input type="hidden" name="section" value="{{$value->section_id}}">
                              <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                              <input type="number" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                            </td>
                          @endif
                          @if($Mco->LocalStockColumns->type == 'change' )
                            <td class="change">
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <input type="hidden" name="section" value="{{$value->section_id}}">
                              <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                              <input type="hidden" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                              <span class="spa">{{ $Mco->value }}</span>
                              
                            </td>
                          @endif
                          @if($Mco->LocalStockColumns->type == 'state' )
                            <td class="state">
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <input type="hidden" name="section" value="{{$value->section_id}}">
                              <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                              <input type="hidden" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                              
                              @if($Mco->value === 'up' )
                                <img class="image" src="https://admin.elkenany.com/uploads/full_images/arrows3-01.png" style="width:20px">
                              @endif
                              @if($Mco->value === 'down' )
                                <img class="image"  src="https://admin.elkenany.com/uploads/full_images/arrows3-02.png" style="width:20px">
                              @endif
                              @if($Mco->value === 'equal' )
                                <img class="image" src="https://admin.elkenany.com/uploads/full_images/arrows3-03.png" style="width:20px">
                              @endif
                            </td>
                          @endif
                          @if($Mco->LocalStockColumns->type == null )
                            <td>
                              <input type="hidden" name="id" value="{{$value->id}}">
                              <input type="hidden" name="section" value="{{$value->section_id}}">
                              <input type="hidden" class="form-control idata{{$Mco->id}}" name="Mco_id[]" value="{{$Mco->column_id}}" />
                              <input type="text" class="form-control data{{$Mco->id}}" value="{{ $Mco->value }}" name="value[]">    
                            </td>
                          @endif
                          @endforeach
                          @endif
                              <td style="display:inline-flex">
                              <button id="submitForm" style="display:inline;border-bottom-left-radius: 0px;border-top-left-radius: 0px;" data-section= "{{$value->section_id}}" data-member = "{{$value->id}}" class="btn btn-primary btn-sm update_col" type="submit">  تحديث</button>
                              <div class="dropdown" style="display:inline">
                                <button  style="display:inline;width:30px;padding:5px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a href="{{route('showmovements',$value->id)}}" style="width:100%;text-align:center" class="dropdown-item " type="submit"> التحديثات</a>
                                  @if($value->status == 1)
                                  <button data-member = "{{$value->id}}" style="width:100%" class="btn btn-light warning" type="button">عدم تحديث</button>
                                  @endif
                                </div>
                              </div>
                              </td>
                              </form>
                              <td>
                                <form action="{{route('Deletemember')}}" method="post" style="display: inline-block;">
                                  {{csrf_field()}}
                                  <input type="hidden" name="id" value="{{$value->id}}">
                                  <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                                </form>
                              </td>
                        </tr>
                      @endif
                  @endforeach
                  @endif
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
 
$('.update').on('click',function(){
      $('.submit').click();
  })


  $('.addd').on('click',function(){
      $('.submit').click();
  })

  //edit columns
  $('.edit').on('click',function(){
      var id         = $(this).data('id')
      var name       = $(this).data('name')
      

      $('.item_name').text(name)
      $("input[name='edit_columns_id']").val(id)
      $("input[name='edit_columns_name']").val(name)
      
      
  });


$(".update_col").on('click',function(e){
  e.preventDefault();
  var id = $(this).data('member')
  var $el = $(this).parent().parent();
  var $nn = $(this).parent().siblings('.nam').children('.nem');
  var $state = $(this).parent().siblings('.state').children('.image');
  var $change = $(this).parent().siblings('.change').children('.spa');
  jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('updateMember')}}",
        type: 'post',
        data:  $('#addform'+id).serialize(),
        success: function (result,status){


          if(result != ''){
              $el.css("background-color", "rgb(9 181 11 / 20%)");
              $nn.css("color", "green");
              console.log(result)
              $change.html(result);
              toastr.success('تم حفظ التعديلات بنجاح')
              if(result < 0)
              {
                  var url =  'https://admin.elkenany.com/uploads/full_images/arrows3-02.png'
                  $state.attr('src',url);

              }
              if(result > 0)
              {
                  var url =  'https://admin.elkenany.com/uploads/full_images/arrows3-01.png'
                  $state.attr('src',url);
              }
              if(result == 0)
              {
                  var url =  'https://admin.elkenany.com/uploads/full_images/arrows3-03.png'
                  $state.attr('src',url);
              }
          }

        }
    });


});


$(".alll").on('click',function(){

  
    $(".update_col").click();

  
});


$(".warning").on('click',function(e){
      e.preventDefault();
      var id = $(this).data('member')
      var $ele = $(this);
      var $el = $(this).parent().parent().parent().parent();
      var $nn = $(this).parent().parent().parent().siblings('.nam').children('.nem');
      jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('checkMember')}}",
        type: 'post',
        data:  {
          _token: '{{ csrf_token() }}',
          "id": id,
        },
        success: function (data,status){
          $ele.fadeOut().remove();
          $el.css("background-color", "rgb(255 3 3 / 20%)");
          $nn.css("color", "red");
          console.log(data)
          toastr.success('تم حفظ التعديلات بنجاح')
        }
    });
   
});
   
</script>
@endsection


