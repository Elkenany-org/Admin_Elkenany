@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة المنتجات </h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة منتج 
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم المنتج</th>
                  <th>التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($products as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->name}}</td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id    = "{{$value->id}}"
                        data-name  = "{{$value->name}}"
                        data-image = "{{$value->image}}"
                        >  تعديل <i class="fas fa-edit"></i></a>
                        <form action="{{route('Deleteproducts')}}" method="post" style="display: inline-block;">
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
        </div>

        {{-- add product modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">إضافة منتج جديد</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('Storeproduct')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <label>إسم المنتج</label> <span class="text-danger">*</span>
{{--                    <input type="text" name="name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">--}}
                  <textarea id="names" name="names" class="form-control" rows="6" cols="50" required="" placeholder="ادخل المنتجات " style="margin-bottom: 10px"></textarea>
{{--                    <label style="margin-top: 10px;display: block;" >إختيار صورة <span class="text-primary"> * </span></label></br>--}}
{{--                    <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">--}}
{{--                    <img src="{{asset('dist/img/placeholder.png')}}" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar()" id="avatar">--}}
                    <button type="submit" id="submit" style="display: none;"></button>
              </form>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-outline-light save">حفظ</button>
              <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
            </div>
          </div>
        </div>
      </div>


        {{-- edit product modal --}}
      <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">تعديل منتج : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('Updateproduct')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_id" value="">
                    <label>إسم المنتج</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_name" class="form-control" required="" style="margin-bottom: 10px">
                    <label  style="margin-top: 10px;display: block;">إختيار صورة <span class="text-primary"> * </span></label></br>
                    <input type="file" name="edit_image" accept="image/*" onchange="loadAvatar1(event)" style="display: none;">
                    <img src="" class="test" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar1()" id="avatar1">
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

function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar1(){$("input[name='edit_image']").click()}
var loadAvatar1 = function(event) {
var output = document.getElementById('avatar1');
output.src = URL.createObjectURL(event.target.files[0]);
};
// add section


$('.save').on('click',function(){
    $('#submit').click();
})


//edit section
$('.edit').on('click',function(){
    var id         = $(this).data('id')
    var name       = $(this).data('name')
    var image      = $(this).data('image')

  

    
    $('.item_name').text(name)
    $("input[name='edit_id']").val(id)
    $("input[name='edit_name']").val(name)
  
    var url =  '{{ url("uploads/products/avatar/") }}/' + image
    $('.test').attr('src',url);

    
})

// update section
$('.update').on('click',function(){
    $('#update').click();
})
</script>
@endsection

