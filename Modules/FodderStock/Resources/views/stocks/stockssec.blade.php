@extends('layouts.app')

@section('style')
<style type="text/css">
.dataTables_paginate{
  display:none;
}
.dataTables_info{
  display:none;
}
.dataTables_filter{
  display:none;
}
.dataTables_length{
  display:none;
}

</style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> بورصة سلع  {{$company->name}} </h5>
              <a href="{{route('addstocks')}}" class="btn btn-primary" style="float: left;">
                    إضافة صنف لبورصة 
                    <i class="fas fa-plus"></i>
              </a>
                 <button  style="float: left;margin-left:10px;height:38px;" class="btn btn-success btn-sm alll" type="submit">  تحديث الكل</button>
            </div>
              @foreach($subs as $key => $val)

            <!-- /.card-header -->
            <div class="card-body">
              <btn href=""  onclick="$('#profileCard{{$val->id}}').slideToggle(1000)" class="btn btn-primary">
                   {{$val->name}} - {{$val->Section->name}} 
                    <i class="fas fa-plus"></i>
              </btn>
              <table class="table table-bordered table-hover table-striped"   id="profileCard{{$val->id}}" style="margin-top: 20px;display: none">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الصنف</th>
                  <th> السعر</th>
                  <th>مقدار التغير</th>
                  <th>اتجاه السعر</th>
                  
                  <th>التحديث</th>
                  <th>الحذف</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stocks as $key => $value)
             @if($value->sub_id === $val->id)
                      <tr>
                    <form action="{{route('updateMemberfodder')}}" method="post" id="form{{$value->id}}" style="display: inline-block;">
                    {{ csrf_field() }}
                      <input type="hidden" class="form-control" value="{{$value->id}}" name="id">  
                          <td>{{$key+1}}</td>
                         
                          <td>{{$value->StockFeed->name}}</td>
                          <td>
                          <input type="number" class="form-control" value="{{$value->LastMovement()->price}}" name="price">    
                          </td>
                          <td class="change">
                              <span class="spa">{{$value->LastMovement()->change}}</span>
                          
                          </td>
                          <td class="state">
                            @if($value->LastMovement()->status === 'up' )
                              <img class="image" src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Green-Up-Arrow.svg" style="width:20px">
                            @endif
                            @if($value->LastMovement()->status === 'down' )
                              <img class="image" src="https://freesvg.org/img/arrowdownred.png" style="width:20px">
                            @endif
                            @if($value->LastMovement()->status === 'equal' )
                              <img class="image" src="https://www.pngrepo.com/png/106212/180/equal.png" style="width:20px">
                            @endif
                            @if($value->LastMovement()->status == null )
                            {{$value->LastMovement()->status}}
                            @endif
                          
                          </td>
                        
                          <td style="display:inline-flex">
                          <button id="submitForm" style="display:inline;border-bottom-left-radius: 0px;border-top-left-radius: 0px;" data-section= "{{$value->section_id}}" data-member = "{{$value->id}}" class="btn btn-primary btn-sm updates" type="submit">  تحديث</button>
                          <div class="dropdown" style="display:inline">
                            <button  style="display:inline;width:30px;padding:5px;border-bottom-right-radius: 0px;border-top-right-radius: 0px;" class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <a href="{{route('showmovementsfodder',$value->id)}}" style="width:100%;text-align:center" class="dropdown-item " type="submit"> التحديثات</a>
        
                              <button data-member = "{{$value->id}}" style="width:100%" class="btn btn-light warning" type="button">عدم تحديث</button>

                            </div>
                          </div>
                          </td>
                          
                      </form>
                      <td>
                          <form action="{{route('Deletememberf')}}" method="post" style="display: inline-block;">
                            {{csrf_field()}}
                            <input type="hidden" name="id" value="{{$value->id}}">
                            <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                          </form>
                      </td>
                      </tr>
                 @endif
                @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
            @endforeach
          </div>
        </div>
        {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة ببورصة الاعلاف</p>
            </div>
          </div>
          </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
  
$(".updates").on('click',function(e){
  e.preventDefault();
  var id = $(this).data('member');
  var $el = $(this).parent().parent();
  var $state = $(this).parent().siblings('.state').children('.image');
  var $change = $(this).parent().siblings('.change').children('.spa');
  jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('updateMemberfodder')}}",
        type: 'post',
        data:  $('#form'+id).serialize(),
        success: function (result,status){
          $(".warning").fadeIn();
          $el.css("background-color", "rgb(9 181 11 / 20%)");
     
          console.log(result)
          $change.html(result); 
          if(result < 0)
          {
            var url =  'https://freesvg.org/img/arrowdownred.png'
            $state.attr('src',url); 
            
          }
          if(result > 0)
          {
            var url =  'https://upload.wikimedia.org/wikipedia/commons/f/fe/Green-Up-Arrow.svg'
            $state.attr('src',url); 
          }
          if(result == 0)
          {
            var url =  'https://www.pngrepo.com/png/106212/180/equal.png'
            $state.attr('src',url); 
          }
          toastr.success('تم حفظ التعديلات بنجاح')
        }
    });


});

$(".alll").on('click',function(){

  
    $(".updates").click();

  
});



$(".warning").on('click',function(e){
      e.preventDefault();
      var id = $(this).data('member');
      var $ele = $(this);
      var $el = $(this).parent().parent().parent().parent();
      jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
    $.ajax(
    {
        url: "{{route('checkfodder')}}",
        type: 'post',
        data:  {
          _token: '{{ csrf_token() }}',
          "id": id,
        },
        success: function (data,status){
          $ele.fadeOut();
          $el.css("background-color", "rgb(255 3 3 / 20%)");
          console.log(data)
          toastr.success('تم حفظ التعديلات بنجاح')
        }
    });
   
});
</script>
@endsection

