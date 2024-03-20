@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-sm-12">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h5 class="m-0" style="display: inline;">قائمة طلبات المُزارعين <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>الاسم</th>
                <th> العنوان</th>
                <th>الحالة</th>
                <th>التاريخ</th>
                <th>التحكم</th>
              </tr>
              </thead>
              <tbody>
              @foreach($orders as $key => $value)
                <tr>
                  <td>{{$key+1}}</td>
                  <td style="padding-top: 1px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/farmers/avatar/'.$value->WaferFarmer->avatar)}}" style="border-radius: 50%;display: inline;width: 2.5rem;"></td>
                  <td>{{$value->WaferFarmer->name}}</td>
                  <td>{{$value->title}}</td>
                  <td>
                    @if($value->status === 0)
                      <span class="badge bg-primary">جاري المراجعة</span>
                    @endif

                    @if($value->status === 1)
                      <span class="badge bg-success">تم المراجعة</span>
                    @endif
                  </td>
                  <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                  <td>
                    <a href="" 
                      class="btn btn-info btn-sm edit"
                      data-toggle="modal"
                      data-target="#modal-update"
                      data-id      = "{{$value->id}}"
                      data-title   = "{{$value->title}}"
                      data-content = "{{$value->content}}"
                      data-manage  = "{{$value->management_response}}"
                      >  تعديل <i class="fas fa-edit"></i></a>
                    <form action="{{route('Deletefarmerorder')}}" method="post" style="display: inline-block;">
                      {{csrf_field()}}
                      <input type="hidden" name="id" value="{{$value->id}}">
                      <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
            <div class="modal-content bg-secondary">
              <div class="modal-body">
              <p>هذه الصفحة خاصة  بطلبات المُزارعين</p>
              </div>
            </div>
          </div>
        </div>
  </div>

{{-- edit order modal --}}
<div class="modal fade" id="modal-update">
  <div class="modal-dialog">
    <div class="modal-content bg-info">
      <div class="modal-header">
        <h4 class="modal-title">تعديل الطلب : <span class="item_name"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form action="{{route('Updatefarmerorder')}}" method="post" enctype="multipart/form-data">
              {{csrf_field()}}
              <input type="hidden" name="edit_id" value="">
              <label>العنوان</label> <span class="text-primary">*</span>
              <h5 class="title"></h5></br>
              <label>الطلب</label> <span class="text-primary">*</span>
              <h6 class="cont"></h6></br>
              <label>الرد <span class="text-danger">*</span></label>
							<textarea class="form-control" rows="3" name="management_response" placeholder="الرد" required></textarea>
              <button type="submit" id="update" style="display: none;"></button>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-outline-light update">تحديث</button>
        <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

</div>
@endsection

@section('script')
<script type="text/javascript">

//edit order
$('.edit').on('click',function(){
    var id      = $(this).data('id')
    var title   = $(this).data('title')
    var content = $(this).data('content')
    var manage  = $(this).data('manage')

  
 
    
    $('.title').text(title)
    $('.cont').text(content)
    $("input[name='edit_id']").val(id)
    $("textarea[name='management_response']").html(manage)

    
})

// update order
$('.update').on('click',function(){
    $('#update').click();
})
</script>
@endsection