@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة الاعضاء <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الصوره</th>
                  <th>الإسم</th>
                  <th>البريد الإلكتروني</th>
                  <th>الهاتف</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody>
                @foreach($customers as $key => $value)
	                <tr>
	                  <td>{{$key+1}}</td>
	                  <td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/customers/avatar/'.$value->avatar)}}" style="display: inline;width: 2.5rem;"></td>
	                  <td>{{$value->name}}</td>
	                  <td>{{$value->email}}</td>
                    <td>{{$value->phone}}</td>
                    <td>
		                <a href="{{route('eidtcustomer',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('deletecustomer')}}" method="post" style="display: inline-block;">
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
          <p>هذه الصفحة خاصة   بالاعضاء</p>
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