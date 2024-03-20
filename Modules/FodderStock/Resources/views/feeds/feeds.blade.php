@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة اصناف العلف   {{$sub->name}} <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                     إضافة صنف  
                     <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <button type="button" class="btn btn-info" style="float: left" data-toggle="modal" data-target="#modal-edit-all"> تعديل الكل<i class="fas fa-edit"></i></button>
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>إسم الصنف</th>
                  <th>القسم </th>
                  <th>النوع </th>
                  <th> الحالة</th>
                  <th>التاريخ</th>
                  
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($feeds as $key => $value)
                    <tr>
                      <td><input type="checkbox" class="check" value="{{$value->id}}"> {{$key+1}}</td>
                      <td>{{$value->name}}</td>
                      <td>{{$value->Section->name}}</td>

                      <td>
                      @if($value->MiniSub != null)
                      {{$value->MiniSub->name}}
                      @endif
                      </td>
                      <td>
                      @if($value->fixed === 1)
                      مثبت
                      @else
                      غير مثبت
                      @endif
                      </td>
                      <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td>
                        <a href="" 
                        class="btn btn-info btn-sm edit"
                        data-toggle="modal"
                        data-target="#modal-update"
                        data-id    = "{{$value->id}}"
                        data-name  = "{{$value->name}}"
                        data-section = "{{$value->section_id}}"
                        data-mini = "{{$value->mini_id}}"
                        data-fixed = "{{$value->fixed}}"
                        >  تعديل <i class="fas fa-edit"></i></a>
                        <form action="{{route('Deletefeed')}}" method="post" style="display: inline-block;">
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
              <form action="{{route('Storefeed')}}" method="post">
                    {{csrf_field()}}

                  <ul>
                      <li><label>القسم: </label> <span> {{$sub->section->name}}</span></li>
                      <li><label>القسم الفرعي: </label> <span> {{$sub->name}}</span></li>
                  </ul>
                  <input type="hidden" name="section_id" value="{{$sub->id}}">
                    <label>إسم الصنف</label> <span class="text-danger">*</span>
                    <input type="text" name="name" class="form-control" placeholder="إسم الصنف " required="" style="margin-bottom: 10px">
{{--                    <label>القسم </label> <span class="text-danger">*</span>--}}
{{--                    <select name="section_id" class="form-control">--}}
{{--                        @foreach($sections as $value)--}}
{{--                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    <label>قسم العلف </label> <span class="text-danger">*</span>
                    <select name="mini_id" class="form-control">
                        @foreach($minies as $value)
                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>
                        @endforeach
                    </select>
                    <label> النوع </label> <span class="text-danger">*</span>
                    <select name="fixed" required class="form-control">
                          <option value="0">غير مثبت</option>
                          <option value="1">مثبت</option>
                         
                    </select>
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
              <form action="{{route('Updatefeed')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                  <ul>
                      <li><label>القسم: </label> <span> {{$sub->section->name}}</span></li>
                      <li><label>القسم الفرعي: </label> <span> {{$sub->name}}</span></li>
                  </ul>
                    <input type="hidden" name="edit_id" value="">
                    <label>إسم الصنف</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_name" class="form-control" required="" style="margin-bottom: 10px">
{{--                    <label>القسم </label> <span class="text-danger">*</span>--}}
{{--                    <select name="edit_section_id" class="form-control">--}}
{{--                        @foreach($sections as $value)--}}
{{--                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    <label>قسم العلف </label> <span class="text-danger">*</span>
                    <select name="edit_mini_id" class="form-control">
                        @foreach($minies as $value)
                            <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>
                        @endforeach
                    </select>
                    <label> النوع </label> <span class="text-danger">*</span>
                    <select name="edit_fixed" required class="form-control">
                          <option value="0">غير مثبت</option>
                          <option value="1">مثبت</option>
                         
                    </select>
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

{{-- Start edit all --}}
        <div class="modal fade" id="modal-edit-all">
            <div class="modal-dialog">
                <div class="modal-content bg-info">
                    <div class="modal-header">
                        <h4 class="modal-title">تعديل الاصناف : </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <form action="{{route('UpdateAllFeedsItem')}}" method="POST">
                    <div class="modal-body">

                            {{csrf_field()}}
                            <ul>
                                <li><label>القسم: </label> <span> {{$sub->section->name}}</span></li>
                                <li><label>القسم الفرعي: </label> <span> {{$sub->name}}</span></li>
                            </ul>
                            <input type="hidden" name="edit_all_ids" class="edit_all_ids" value="">
                            <label>قسم العلف </label> <span class="text-danger">*</span>
                            <select name="mini_id" class="form-control">
                                @foreach($minies as $value)
                                    <option value="{{$value->id}}">{{$value->name}} - {{$value->Section->name}}</option>
                                @endforeach
                            </select>
{{--                            <label> النوع </label> <span class="text-danger">*</span>--}}
{{--                            <select name=fixed" required class="form-control">--}}
{{--                                <option value="0">غير مثبت</option>--}}
{{--                                <option value="1">مثبت</option>--}}

{{--                            </select>--}}
{{--                            <button type="submit" id="edit" style="display: none;"></button>--}}

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" class="btn btn-outline-light edit">تحديث</button>
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
{{-- End edit all --}}
    </div>
@endsection

@section('script')


    <script type="text/javascript">

        $(function () {

            var all = [];
            $("#example1").on('click','.check',function () {

                if($(this).is(':checked')){
                    //add item to array
                    all.push($(this).val());
                }else {
                    //remove item from array
                    for( var i = 0; i < all.length; i++){
                        if ( all[i] === $(this).val()) {
                            all.splice(i, 1);
                        }
                    }

                }

                $('.edit_all_ids').val(all);
            });

        });


        // $('.edit').on('click',function(){
        //     $('#edit').click();
        // })
    </script>




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
        var mini = $(this).data('mini')
        var fixed = $(this).data('fixed')

     

        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_name']").val(name)
      
        $("select[name='edit_section_id'] > option").each(function() {
        if($(this).val() == section)
        {
          $(this).attr("selected","")
        }
      });

       
      $("select[name='edit_mini_id'] > option").each(function() {
        if($(this).val() == mini)
        {
          $(this).attr("selected","")
        }
      });

      $("select[name='edit_fixed'] > option").each(function() {
            if($(this).val() == fixed)
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

