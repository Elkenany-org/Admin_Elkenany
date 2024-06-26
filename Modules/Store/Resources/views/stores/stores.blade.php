@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0" style="display: inline;">قائمة اعلانات المتجر<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>العنوان</th>
                            <th>الهاتف</th>
                            <th>الحالة</th>
                            <th>عدد المشاهدات</th>
                            <th> التاريخ</th>
                            <th>التحكم</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($stores as $key => $value)
                            <tr>
                                <td>{{$key+1}}</td>
                                @if($value->admin_id == null)
                                    <td><a href="{{route('eidtcustomer',$value->Customer->id)}}"  style="padding: 0px;" class="nav-link">{{$value->Customer->name}}</a></td>
                                @endif
                                @if($value->user_id == null)
                                    <td><a href="{{route('eidtuser',$value->User->id)}}"  style="padding: 0px;" class="nav-link">{{$value->User->name}}</a></td>
                                @endif
                                <td>{{$value->title}}</td>
                                <td>{{$value->phone}}</td>

                                @if($value->approved == '0')
                                    <td>غير مقبول</td>
                                @endif
                                @if($value->approved == '1')
                                    <td> مقبول</td>
                                @endif
                                @if($value->approved == '2')
                                    <td>يحتاج المراجعة</td>
                                @endif
                                <td>{{$value->view_count}}</td>
                                <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                                <td>
                                    <a href="{{route('Editstoreads',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
                                    <form action="{{route('Deletestoreads')}}" method="post" style="display: inline-block;">
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
                        <p>هذه الصفحة خاصة  بجميع  الاعلانات</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">

    </script>
@endsection