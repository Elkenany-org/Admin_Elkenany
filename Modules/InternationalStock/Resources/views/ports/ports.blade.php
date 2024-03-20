@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة المواني</h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة ميناء 
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم  الميناء</th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ports as $key => $value)
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
                        data-name    = "{{$value->name}}"
                     
                        >  تعديل <i class="fas fa-edit"></i></a>
                        <form action="{{route('deleteports')}}" method="post" style="display: inline-block;">
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
      {{-- add area modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">إضافة ميناء جديد</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('storeports')}}" method="post">
                    {{csrf_field()}}
                    <div class="row">
                      <div class="col-sm-12">
                        <label>إسم  الميناء</label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control" placeholder="إسم  الميناء " required="" style="margin-bottom: 10px">
                      </div>
                  
                    </div>
                  
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

      {{-- edit area modal --}}
      <div class="modal fade" id="modal-update">
        <div class="modal-dialog">
          <div class="modal-content bg-info">
            <div class="modal-header">
              <h4 class="modal-title">تعديل ميناء : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('updateports')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="row">
                      <div class="col-sm-12">
                        <input type="hidden" name="edit_id" value="">
                        <label>إسم  الميناء</label> <span class="text-danger">*</span>
                        <input type="text" name="edit_name" class="form-control" placeholder="إسم الميناء " required="" style="margin-bottom: 10px">
                      </div>
                    </div>  
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
    // add section
   

    $('.save').on('click',function(){
        $('#submit').click();
    })

  


    //edit section
    $('.edit').on('click',function(){
        var id       = $(this).data('id')
        var name     = $(this).data('name')
     

     

        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_name']").val(name)
      

       

       
    })

    // update section
    $('.update').on('click',function(){
        $('#update').click();
    })
</script>
@endsection

