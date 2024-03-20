@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">قائمة الإشعارات</h5>
                    {{--                    data-toggle="modal" data-target="#modal-primary"--}}
{{--                    <a href="{{route('create_notification')}}" class="btn btn-primary"  style="float: left;">--}}
{{--                        إضافة إشعار--}}
{{--                        <i class="fas fa-plus"></i>--}}
{{--                    </a>--}}
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الإشعار</th>
                            <th>الصوره</th>
                            <th>التاريخ / الوقت</th>
                            <th>المصدر</th>
                            <th>إسم  الشركه</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($notifications as $key => $value)
                            <tr>

                                <td>{{$loop->iteration}}</td>
                                <td>{{$value->title}}<br>{{$value->desc}}</td>
                                <td>@if($value->image != "")<img src="{{$value->image}}" width="50px" height="50px">@else <img src="https://img.icons8.com/ios/50/000000/image.png"/> @endif</td>
                                <td>{{$value->created_at}}</td>
                                <td>@foreach(config('notification.source') as $k => $v)
                                        @if($k == $value->model_name)
                                            {{$v}}
                                        @endif
                                @endforeach
                                </td>
                                <td>{{$value->company_id != null ? $value->company->name : ''}}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#exampleModalDelete{{$value->id}}" type="button">  حذف <i class="fas fa-trash"></i></button>
                                </td>
                            </tr>

                            {{-- Start Delete modal --}}
                                <div class="modal fade" id="exampleModalDelete{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <form action="{{route('delete_notification_notScheduled',$value->id)}}" method="GET" >
                                        @csrf
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">حذف إشعار </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                هل تريد حذف هذا الإشعار ؟
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                                <button type="submit" class="btn btn-danger">حذف</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            {{-- End Delete modal --}}

                            {{-- Start Edit modal --}}
{{--                            <div class="modal fade" id="exampleModalEdit{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                                <div class="modal-dialog" role="document">--}}
{{--                                    <div class="modal-content">--}}
{{--                                        <form method="POST" action="{{route('editnotification',$value->id)}}" enctype="multipart/form-data">--}}
{{--                                            @csrf--}}
{{--                                            <div class="modal-header">--}}
{{--                                                <h5 class="modal-title" id="exampleModalLabel">تعديل الإشعار</h5>--}}
{{--                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                    <span aria-hidden="true">&times;</span>--}}
{{--                                                </button>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-body">--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="recipient-name" class="col-form-label">العنوان:</label>--}}
{{--                                                    <input type="text" class="form-control" name="title" value="{{$value->title}}">--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group">--}}
{{--                                                    <label for="message-text" class="col-form-label">الوصف:</label>--}}
{{--                                                    <textarea class="form-control" name="body" >{{$value->body}}</textarea>--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <label for="message-text" class="col-form-label">التاريخ:</label>--}}
{{--                                                        <input type="date" class="form-control" name="date_at" value="{{$value->date_at}}">--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <label for="message-text" class="col-form-label">الوقت:</label>--}}
{{--                                                        <input type="time" class="form-control" name="time_at" value="{{$value->time_at}}">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group row">--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <label for="message-text" class="col-form-label">الصوره:</label>--}}
{{--                                                        <input type="file" class="form-control" name="image" >--}}
{{--                                                    </div>--}}
{{--                                                    <div class="col-md-6">--}}
{{--                                                        <img src="{{$value->image_url}}" alt="{{$value->title}}" width="200px" height="150px">--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <div class="modal-footer">--}}
{{--                                                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: auto">إغلاق</button>--}}
{{--                                                <button type="submit" class="btn btn-primary">تعديل</button>--}}
{{--                                            </div>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        {{-- End Edit modal --}}
                        @endforeach
                        </tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        // add section


    </script>
@endsection

