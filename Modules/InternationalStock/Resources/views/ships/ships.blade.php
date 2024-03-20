@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة  حركات السفن<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              <a href="{{route('addships')}}" class="btn btn-primary" style="float: left;">
                     إضافة  حركة جديدة 
                     <i class="fas fa-plus"></i>
                </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>المنشأ</th>
                  <th>الحمولة</th>
  
                  <th> التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ships as $key => $value)
	                <tr>
                    <td>{{$key+1}}</td>
                
                    <td>{{$value->name}}</td>
	                  <td>{{$value->country}}</td>
                    <td>{{$value->load}} طن</td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                    <td>
		                <a href="{{route('Editships',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('Deleteships')}}" method="post" style="display: inline-block;">
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
            <p>هذه الصفحة خاصة  بجميع  حركات السفن</p>
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