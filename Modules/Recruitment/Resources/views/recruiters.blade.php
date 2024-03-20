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
                <!-- /.card-header -->
                <div class="card-body">

                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الشركة</th>
                            <th>الحالة</th>
                            <th>العميل علي الموقع </th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody class="rowo">
                        @foreach($recruiters as $key => $value)
                            <tr >
                                <td>{{$key+1}}</td>
                                <td>{{$value->name}}</td>
                                <td>{{$value->Company->name}}</td>

                                @if($value->verified_company == '0')
                                    <td >تحت المراجعة</td>
                                @endif

                                @if($value->verified_company == '1')
                                    <td>موافق عليه</td>
                                @endif
                                @if($value->verified_company == '2')
                                    <td >غير موافق عليه</td>
                                @endif
                                <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                                <td>
                                    <a href="{{route('editRecruiter',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
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