@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الاقسام الرئيسية  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                <button type="button" class="btn btn-primary mx-1" data-toggle="modal" data-target="#modal-select" style="float: left;">
                    تحديد قسم رئيسي
                    <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة قسم رئيسي 
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم القسم</th>
                    <th>الحالة</th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sections as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->name}}</td>
                        @if($value->selected == '1')
                            <td>مثبت</td>
                        @endif
                        @if($value->selected == '0')
                            <td>غير مثبت</td>
                        @endif
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id    = "{{$value->id}}"
                        data-name  = "{{$value->name}}"
                        >  تعديل <i class="fas fa-eye"></i></a>
                        <a href="{{route('foddersubsections',$value->id)}}" class="btn btn-primary btn-sm"> الأقسام الفرعية <i class="fas fa-eye"></i></a>
                        <form action="{{route('deletefoddersection')}}" method="post" style="display: inline-block;">
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
            <p>هذه الصفحة خاصة بقائمة الاقسام</p>
            </div>
          </div>
          </div>
        </div>
        {{-- add section modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">إضافة قسم جديد</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('Storefoddersection')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <label>إسم القسم</label> <span class="text-danger">*</span>
                  <input type="text" name="name" class="form-control" placeholder="إسم القسم " required="" style="margin-bottom: 10px">
                  <input type="text" name="type" class="form-control" placeholder="إسم القسم بالانجليزي" required="" style="margin-bottom: 10px">
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


        <div class="modal fade" id="modal-select">
            <div class="modal-dialog">
                <div class="modal-content bg-primary">
                    <div class="modal-header">
                        <h4 class="modal-title">تحديد قسم رئيسي</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('selectfoddersection')}}" method="post">
                            {{csrf_field()}}
                            <label>إسم القسم</label> <span class="text-danger">*</span>
                            <select name="id" class="form-control" required>
                                <option value="" disabled selected>إختيار </option>
                                @foreach($sections as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                            </select>
                            <button type="submit" id="select" style="display: none;"></button>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light select">تحديد</button>
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
              <h4 class="modal-title">تعديل قسم : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{route('updatefoddersection')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_id" value="">
                    <label>إسم القسم</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_name" class="form-control"  style="margin-bottom: 10px">
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
     

        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_name']").val(name)
    

       
    })

    // update section
    $('.update').on('click',function(){
        $('#update').click();
    })

// select section
$('.select').on('click',function(){
    $('#select').click();
})
</script>
@endsection

