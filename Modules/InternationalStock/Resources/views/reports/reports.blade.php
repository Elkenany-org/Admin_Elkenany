@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;"> التقرير الاسبوعي   <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              <a href="{{route('Addinterreports')}}" class="btn btn-primary" style="float: left;">
                     إضافة تحليل  جديد 
                     <i class="fas fa-plus"></i>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الصورة</th>
                  <th>العنوان</th>
                  <th> التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($news as $key => $value)
	                <tr>
                    <td>{{$key+1}}</td>
                    <td><img alt="Avatar" class="table-avatar" src="{{asset('uploads/news/avatar/'.$value->image)}}" style="width: 50px;"></td>
	                  <td>{{$value->title}}</td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                    <td>
		                <a href="{{route('Editinterreports',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('Deleteinterreports')}}" method="post" style="display: inline-block;">
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
          {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة  بالتقرير الاسبوعي</p>
            </div>
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