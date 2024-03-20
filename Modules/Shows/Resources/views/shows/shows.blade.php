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
              <h5 class="m-0" style="display: inline;">قائمة المعارض  <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="col-sm-4" style="margin-top: 10px;margin-bottom: 20px" >
                    <label style="margin-top: 10px;margin-bottom: 20px" class="text-primary">  ابحث عن  اسم المعرض <span class="text-danger">*</span></label>
                    <input type="search" class="form-control sec_search" name="sec_search">
                </div>
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الصوره</th>
                  <th>الاسم</th>
                  <th>التقييم</th>
                  <th>التاريخ</th>
                  <th>التحكم</th>
                </tr>
                </thead>
                <tbody class="rowo">
                @foreach($shows as $key => $value)
	                <tr >
	                  <td>{{$key+1}}</td>
                    <td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/show/images/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
	                  <td>{{$value->name}}</td>
                    <td>{{$value->rate}}</td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
	                  <td>
		                <a href="{{route('editshow',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
	                  	<form action="{{route('deleteshow')}}" method="post" style="display: inline-block;">
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
                {{$shows->links()}}
              </div>
            </div>
          </div>
          warning
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

// search showes
$(document).on('keyup','.sec_search', function(){
var data = {
    search     : $(this).val(),
    _token     : $("input[name='_token']").val()
}

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
@endsection