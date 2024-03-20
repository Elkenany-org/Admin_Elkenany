@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0" style="display: inline;">قائمة الصور الرئيسية  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                    إضافة صورة رئيسية
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-hover table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>النوع</th>
                        <th>الوصف</th>
                        <th>الخدمات</th>
                        <th>الاكثر زيارة</th>
                        <th>الاحدث</th>
                        <th>اللينك</th>
                        <th>التاريخ</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($images as $key => $value)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td><img alt="Avatar" class="table-avatar" src="{{asset('uploads/main/home/'.$value->image)}}" style="width: 50px;"></td>
                        <td>{{$value->type}}</td>
                        <td>{{$value->description}}</td>
                        <td>{{$value->services? $value->Service->name : '-'}}</td>
                        <td>{{$value->Visited? $value->Visited->name : '-'}}</td>
                        <td>{{$value->Newest ? $value->Newest->name : '-'}}</td>
                        <td>{{$value->link}}</td>

                        <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                        <td>
                            <a href=""
                               class="btn btn-info btn-sm edit"
                               data-toggle="modal"
                               data-target="#modal-update"
                               data-id    = "{{$value->id}}"
                               data-image = "{{$value->image}}"
                               data-type = "{{$value->type}}"
                               data-desc = "{{$value->description}}"
                               data-services = "{{$value->services? $value->Service->id : '-'}}"
                               data-visited = "{{$value->Visited? $value->Visited->id : '-'}}"
                               data-newest = "{{$value->Newest ? $value->Newest->id : '-'}}"
                               data-link = "{{$value->link}}"

                            >  تعديل <i class="fas fa-edit"></i></a>
                            <form action="{{route('deletemainImage')}}" method="post" style="display: inline-block;">
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
                    <h4 class="modal-title">إضافة صورة جديدة</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('storemainImage')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}


                        <label style="margin-top: 10px;display: block;" >إختيار الصورة <span class="text-danger">*</span></label>
                        <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;" >
                        <img src="{{asset('dist/img/placeholder.png')}}" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar()" id="avatar" >

                         <label>النوع</label>
                        <select name="type" class="form-control" >
                            <option value="" disabled selected>إختيار </option>
                            @foreach($types as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>

                        <label> الوصف</label> <span class="text-danger">*</span>
                        <input type="text" name="desc" class="form-control" placeholder="الوصف" required="" style="margin-bottom: 10px">

                        <label>الخدمات</label>
                        <select name="services" class="form-control" >
                            <option value="" disabled selected>إختيار </option>
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>


                        <label>القسم الاكثر زيارة</label>
                        <select name="visited" class="form-control" >
                            <option value="" disabled selected>إختيار </option>
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>

                        <label>القسم الاحدث</label>
                        <select name="newest" class="form-control" >
                            <option value="" disabled selected>إختيار </option>
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>

                        <label> لينك الموقع</label> <span class="text-danger">*</span>
                        <input type="text" name="link" class="form-control" placeholder="لينك الموقع" required="" style="margin-bottom: 10px">


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
                    <h4 class="modal-title">تعديل الصورة : </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('updatemainImage')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="edit_id" value="">

                        <label  style="margin-top: 10px;display: block;">إختيار صورة <span class="text-primary"> * </span></label>
                        <input type="file" name="edit_image" accept="image/*" onchange="loadAvatar1(event)" style="display: none;">
                        <img src="" class="test" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar1()" id="avatar1">

                        <label>النوع</label>
                        <select name="edit_type" class="form-control" >
                            @foreach($types as $value)
                                <option value="{{$value}}">{{$value}}</option>
                            @endforeach
                        </select>

                        <label>الوصف</label> <span class="text-danger">*</span>
                        <input type="text" name="edit_desc" class="form-control" style="margin-bottom: 10px">

                        <label>الخدمات</label>
                        <select name="edit_services" class="form-control" >
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>

                        <label>القسم الاكثر زيارة</label>
                        <select name="edit_visited" class="form-control" >
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>

                        <label>القسم الاحدث</label>
                        <select name="edit_newest" class="form-control" >
                            @foreach($services as $value)
                                <option value="{{$value->id}}">{{$value->name}}</option>
                            @endforeach
                        </select>

                        <label>لينك الموقع</label> <span class="text-danger">*</span>
                        <input type="text" name="edit_link" class="form-control" style="margin-bottom: 10px">


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
        console.log( output.src)
    };

    // add section


    $('.save').on('click',function(){
        $('#submit').click();
    })




    //edit section
    $('.edit').on('click',function(){
        var id         = $(this).data('id')
        var type       = $(this).data('type')
        var desc       = $(this).data('desc')
        var services       = $(this).data('services')
        var newest       = $(this).data('newest')
        var visited       = $(this).data('visited')
        var link       = $(this).data('link')

        var image      = $(this).data('image')


        $("input[name='edit_id']").val(id)
        $("select[name='edit_type']").val(type)
        $("input[name='edit_desc']").val(desc)
        $("select[name='edit_services']").val(services)
        $("select[name='edit_newest']").val(newest)
        $("select[name='edit_visited']").val(visited)
        $("input[name='edit_link']").val(link)


        var url =  '{{ url("uploads/main/home/") }}/' + image
        $('.test').attr('src',url);



    })

    // update section
    $('.update').on('click',function(){
        $('#update').click();
    })
</script>
@endsection

