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
              <h5 class="m-0" style="display: inline;">قائمة المقرات   <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>

                  <th>التاريخ</th>
 
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody class="rowo row_drag">
                @foreach($offices as $key => $value)
	                <tr id="{{$key+1}}">
	                  <td>{{$key+1}}</td>
                    <td>{{$value->name}}</td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                  
	                  <td> 
		                <a href="{{route('editoffices',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('deleteoffices')}}" method="post" style="display: inline-block;">
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
            <div class="row text-center">
              <div class="col-sm-12 text-center">
                {{$offices->links()}}
              </div>
            </div>
          </div>
          {{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة بقائمة المقرات</p>
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

</script>
@endsection