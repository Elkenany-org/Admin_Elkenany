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

    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">قائمة الاقسام الرئيسية  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                        إضافة قسم
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الشركة</th>
                            <th>الحالة</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody class="rowo">
                        @foreach($jobs as $key => $value)
                            <tr >
                                <td>{{$key+1}}</td>
                                <td>{{$value->title}}</td>
                                <td>{{$value->Company->name}}</td>
                                @if($value->approved == '0')
                                    <td>غير موافق عليه </td>
                                @endif
                                @if($value->approved == '1')
                                    <td> موافق عليه </td>
                                @endif
                                <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                                <td>
                                    <a href="{{route('editJob',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
                                    <form action="{{route('deleteJob')}}" method="post" style="display: inline-block;">
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

                {{--warning--}}
                <div class="modal fade" id="modal-secondary">
                    <div class="modal-dialog">
                        <div class="modal-content bg-secondary">
                            <div class="modal-body">
                                <p>هذه الصفحة خاصة بقائمة المعارض</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{csrf_field()}}

            </div>
        </div>


        <div class="modal fade" id="modal-primary">
            <div class="modal-dialog">
                <div class="modal-content bg-primary">
                    <div class="modal-header">
                        <h4 class="modal-title">إضافة قسم جديد</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('storeCategories')}}" method="post" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <label>إسم القسم</label> <span class="text-danger">*</span>
                            <input type="text" name="name" class="form-control" placeholder="إسم القسم " required="" style="margin-bottom: 10px">
                            <label> الاسم بالانجليزي</label> <span class="text-danger">*</span>
                            <input type="text" name="type" class="form-control" placeholder="إسم القسم " required="" style="margin-bottom: 10px">
                            <label> التفاصيل</label> <span class="text-danger">*</span>
                            <input type="text" name="desc" class="form-control" placeholder="تفاصيل القسم " required="" style="margin-bottom: 10px">
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

        @endsection

        @section('script')
            <script type="text/javascript">

                $.ajax({
                    url     : "{{ url('get-showes-ser') }}",
                    method  : 'post',
                    data    : data,
                    success : function(s,result){
                        $('.rowo').html('')
                        $('.rowo').append('')
                        $.each(s,function(k,v){
                            $('.rowo').append(`
    <tr >
	                  <td>${v.id}</td>
                    <td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/show/images/')}}/${v.image}" style="display: inline;width: 2.5rem;"></td>
	                  <td>${v.name}</td>
                    <td>${v.rate}</td>
                    <td> <span class="badge badge-success">${v.created_at}</span></td>
	                  <td>
		                <a href="{{ url('edit-show') }}/${v.id}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('deleteshow')}}" method="post" style="display: inline-block;">
	                  		{{csrf_field()}}
                            <input type="hidden" name="id" value="${v.id}">
	                  		<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
	                  	</form>
	                  </td>
	                </tr>
    `);

                        })

                    }});

                });
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
                    var image      = $(this).data('image')




                    $('.item_name').text(name)
                    $("input[name='edit_id']").val(id)
                    $("input[name='edit_name']").val(name)


                    var url =  '{{ url("uploads/main/") }}/' + image
                    $('.test').attr('src',url);



                })

                // update section
                $('.update').on('click',function(){
                    $('#update').click();
                })
            </script>


@endsection