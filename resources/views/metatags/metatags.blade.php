@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">قائمة ال meta tags  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
{{--                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">--}}
{{--                        إضافة meta tag--}}
{{--                        <i class="fas fa-plus"></i>--}}
{{--                    </button>--}}
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>عنوان السوشيال</th>
                            <th>وصف السوشيال</th>
                            <th>اللينك</th>
                            <th>alt</th>
                            <th>تابع ل</th>
                            <th>التاريخ</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($metatags as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td><img alt="Avatar" class="table-avatar" src="{{asset('uploads/meta/images/'.$value->image)}}" style="width: 50px;"></td>
                                <td>{{$value->title}}</td>
                                <td>{{$value->desc}}</td>
                                <td>{{$value->title_social}}</td>
                                <td>{{$value->desc_social}}</td>
                                <td>{{$value->link}}</td>
                                <td>{{$value->alt}}</td>
                                <td>{{$value->news_id}}</td>
                                <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                                <td>
                                    <a href=""
                                       class="btn btn-info btn-sm edit"
                                       data-toggle="modal"
                                       data-target="#modal-update"
                                       data-id    = "{{$value->id}}"
                                       data-image = "{{$value->image}}"
                                       data-title = "{{$value->title}}"
                                       data-desc = "{{$value->desc}}"
                                       data-title_social = "{{$value->title_social}}"
                                       data-desc_social = "{{$value->desc_social}}"
                                       data-link = "{{$value->link}}"
                                       data-alt = "{{$value->alt}}"

                                    >  تعديل <i class="fas fa-edit"></i></a>


                                    <form action="{{route('deleteMetaTag')}}" method="post" style="display: inline-block;">
                                        {{csrf_field()}}
                                        <input type="hidden" name="id" value="{{$value->id}}">
                                        <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                                    </form>
                                </td>


                            </tr>
                        @endforeach
                        </tfoot>
                    </table>{{ $metatags->links() }}
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


        {{-- edit section modal --}}
        <div class="modal fade" id="modal-update">
            <div class="modal-dialog">
                <div class="modal-content bg-info">
                    <div class="modal-header">
                        <h4 class="modal-title">تعديل meta tag : </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('updateMetaTag')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="edit_id" value="">

                            <label  style="margin-top: 10px;display: block;">إختيار صورة <span class="text-primary"> * </span></label>
                            <input type="file" name="edit_image" accept="image/*" onchange="loadAvatar1(event)" style="display: none;">
                            <img src="" class="test" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar1()" id="avatar1">

                            <label>alt</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_alt" class="form-control" style="margin-bottom: 10px">

                            <label>العنوان</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_title" class="form-control" style="margin-bottom: 10px">

                            <label>الوصف</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_desc" class="form-control" style="margin-bottom: 10px">


                            <label> عنوان السوشيال</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_title_social" class="form-control" style="margin-bottom: 10px">

                            <label> وصف السوشيال</label> <span class="text-danger">*</span>
                            <input type="text" name="edit_desc_social" class="form-control" style="margin-bottom: 10px">

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
            var title       = $(this).data('title')
            var desc       = $(this).data('desc')
            var title_social       = $(this).data('title_social')
            var desc_social       = $(this).data('desc_social')
            var link       = $(this).data('link')
            var image      = $(this).data('image')
            var alt      = $(this).data('alt')


            $("input[name='edit_id']").val(id)
            $("input[name='edit_title']").val(title)
            $("input[name='edit_desc']").val(desc)
            $("input[name='edit_title_social']").val(title_social)
            $("input[name='edit_desc_social']").val(desc_social)
            $("input[name='edit_link']").val(link)
            $("input[name='edit_alt']").val(alt)


            var url =  '{{ url("uploads/meta/images/") }}/' + image
            $('.test').attr('src',url);



        })

        // update section
        $('.update').on('click',function(){
            $('#update').click();
        })
    </script>
@endsection

