@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> قائمة بورصات الأدوية  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة بورصة أدوية 
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم البورصة</th>
                  <th>الترتيب</th>
                  <th>التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sections as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->name}}</td>
              
                      <td> 
                      <form action="{{route('submedicinenumsort')}}" class="sort{{$value->id}}" method="post" style="display: inline-block;">
                          {{csrf_field()}}
                          <input type="hidden" name="id" value="{{$value->id}}">
                          <input type="number" name="sort" value="{{$value->sort}}" data-id= "{{$value->id}}" style="height: 30px;">
                          <button class="btn btn-success sort" type="submit"  data-id= "{{$value->id}}"  style="height: 30px;"> <i class="fas fa-check"></i></button>
                      </form>
                      </td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id         = "{{$value->id}}"
                        data-name       = "{{$value->name}}"
                        data-section_id = "{{$value->section_id}}"
                        data-image = "{{$value->image}}"
                        >  تعديل <i class="fas fa-edit"></i></a>
                        <a href="{{route('medicinestockmembers',$value->id)}}" class="btn btn-primary btn-sm">  اصناف البورصة <i class="fas fa-eye"></i></a>
                        <form action="{{route('deletemedicinesubsection')}}" method="post" style="display: inline-block;">
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

        {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة بقائمة البورصات</p>
            </div>
          </div>
          </div>
        </div>

        {{-- add section modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">إضافة بورصة ادوية جديدة</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('storemedicinesubsection')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <label>إسم البورصة</label> <span class="text-danger">*</span>
                    <input type="text" name="name" class="form-control" placeholder="إسم البورصة " required="" style="margin-bottom: 10px">
                    <label>القسم الرئيسي</label> <span class="text-danger">*</span>
                    <select name="section_id" class="form-control">
                        @foreach($main_sections as $value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>

                    <label style="margin-top: 10px;display: block;" >إختيار صورة <span class="text-primary"> * </span></label>
                    <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                    <img src="{{asset('dist/img/placeholder.png')}}" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar()" id="avatar">
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


        {{-- edit section modal --}}
      <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">تعديل بورصة : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('updatemedicinesubsection')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_id" value="">
                    <label>إسم البورصة</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_name" class="form-control" required="" style="margin-bottom: 10px">
                    <label>القسم الرئيسي</label> <span class="text-danger">*</span>
                    <select name="edit_section_id" class="form-control">
                        @foreach($main_sections as $value)
                            <option value="{{$value->id}}">{{$value->name}}</option>
                        @endforeach
                    </select>

                    <label  style="margin-top: 10px;display: block;">إختيار صورة <span class="text-primary"> * </span></label>
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
    var section_id = $(this).data('section_id')
    var image      = $(this).data('image')
    
    $('.item_name').text(name)
    $("input[name='edit_id']")     .val(id)
    $("input[name='edit_name']")   .val(name)

    var url =  '{{ url("uploads/sections/avatar/") }}/' + image
    $('.test').attr('src',url);

    $("select[name='edit_section_id'] > option").each(function() {
        if($(this).val() == section_id)
        {
          $(this).attr("selected","")
        }
      });
})

// update section
$('.update').on('click',function(){
    $('#update').click();
})
</script>
@endsection

