@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة المنشورات <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>النوع</th>
                  <th> السعر</th>
                  <th>اسم القسم</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $key => $value)
	                <tr>
	                  <td>{{$key+1}}</td>
	                  <td>{{$value->WaferFarmer->name}}</td>
	                  <td>{{$value->item_type}}</td>
                    <td>{{$value->price}}</td>
                    <td>{{$value->Section->name}}</td>
                    <td>
                      <a href="{{route('Showpost',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  بيانات المنشور <i class="fas fa-eye"></i></a>
	                  	<form action="{{route('Deletepost')}}" method="post" style="display: inline-block;">
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
              <p>هذه الصفحة خاصة   بالمنشورات لوافر</p>
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