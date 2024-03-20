@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة اقسام العلف   {{$sub->name}} <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة قسم للاصناف  
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم الصنف</th>
                  <th>القسم </th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($feeds as $key => $value)
                    <tr>
                      <td>{{$key+1}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->Section->name}}</td>
                  
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id    = "{{$value->id}}"
                        data-name  = "{{$value->name}}"
                        data-section = "{{$value->section_id}}"
                        >  تعديل <i class="fas fa-edit"></i></a>
                        <form action="{{route('Deletefeedmini')}}" method="post" style="display: inline-block;">
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
            <p>هذه الصفحة خاصة بقائمة اصناف العلف</p>
            </div>
          </div>
          </div>
        </div>
        {{-- add section modal --}}
      <div class="modal fade" id="modal-primary">
        <div class="modal-dialog">
          <div class="modal-content bg-primary">
            <div class="modal-header">
              <h4 class="modal-title">إضافة صنف جديد</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('Storefeedmini')}}" method="post">
                    {{csrf_field()}}

                      <ul>
                          <li><label>القسم: </label> <span>{{$sub->section->name}}</span></li>
                          <li><label>القسم الفرعي: </label> <span>{{$sub->name}}</span></li>
                      </ul>
                        <label>إسم الصنف</label> <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control" placeholder="إسم الصنف " required="" style="margin-bottom: 10px">
                        <input type="hidden" value="{{$sub->id}}" name="section_id">

{{--                    <input type="text" name="name" class="form-control" value="{{$sub->section->name}}">--}}
{{--                    <label>القسم </label> <span class="text-danger">*</span>--}}
{{--                    <select name="section_id" class="form-control">--}}
{{--                        @foreach($sections as $value)--}}
{{--                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                   
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
              <h4 class="modal-title">تعديل صنف : <span class="item_name"></span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
              <form action="{{route('Updatefeedmini')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_id" value="">
                  <ul>
                      <li><label>القسم: </label> <span>{{$sub->section->name}}</span></li>
                      <li><label>القسم الفرعي: </label> <span>{{$sub->name}}</span></li>
                  </ul>
                    <label>إسم الصنف</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_name" class="form-control" required="" style="margin-bottom: 10px">
{{--                    <label>القسم </label> <span class="text-danger">*</span>--}}
{{--                    <select name="edit_section_id" class="form-control">--}}
{{--                        @foreach($sections as $value)--}}
{{--                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                  
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
        var id         = $(this).data('id')
        var name       = $(this).data('name')
        var section = $(this).data('section')
      

     

        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_name']").val(name)
      
        $("select[name='edit_section_id'] > option").each(function() {
        if($(this).val() == section)
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

