@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 150px;
	}
	#avatar:hover{
		width: 150px;
		cursor: pointer;
	}
</style>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">إضافة مشرف جديد<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>

            <div class="card-body">
            	<form action="{{route('StoreUserm')}}" method="post" enctype="multipart/form-data">
	            	<div class="row">
            			{{csrf_field()}}
	            		{{-- avatar --}}
	            		<div class="col-sm-2" style="margin-bottom: 20px">
	            			<div class="from-group ">
	            				<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
	            				<input type="file" name="avatar" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
	            				<img src="{{asset('dist/img/add_avatar.png')}}" onclick="ChooseAvatar()" id="avatar">
	            			</div>
	            		</div>

	            		{{-- details --}}
	            		<div class="col-sm-10">
	            			<div class="row">
	            				{{-- name --}}
	            				<div class="col-sm-12">
	            					<div class="from-group">
	            						<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
	            						<input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="إسم المستخدم " required="">
	            					</div>
	            				</div>

	            				{{-- email --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
	            						<input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="البريد الإلكتروني" required="">
	            					</div>
	            				</div>

	            				{{-- password --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
	            						<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" required="">
	            					</div>
	            				</div>

	            				{{-- active --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">الحالة : <span class="text-danger">*</span></label>
	            						<select class="form-control" name="active">
	            							<option value="1">نشط</option>
	            							<option value="0">حظر</option>
	            						</select>
	            					</div>
	            				</div>

	            				{{-- permission --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">الصلاحية : <span class="text-danger">*</span></label>
	            						<select class="form-control" name="role">
	            							<option value="0">بدون</option>
	            							@foreach($roles as $value)
	            								<option value="{{$value->id}}">{{$value->role}}</option>
	            							@endforeach
	            						</select>
	            					</div>
	            				</div>

	            				{{-- submit --}}
	            				<div class="col-sm-4 offset-3" style="margin-top: 20px">
	            					<button class="btn btn-outline-primary btn-block">حفظ</button>
	            				</div>
	            			</div>
	            		</div>
	            	</div>
            	</form>
            </div>
          </div>
        </div>
		{{--warning--}}
		<div class="modal fade" id="modal-secondary">
			<div class="modal-dialog">
			<div class="modal-content bg-secondary">
				<div class="modal-body">
				<p>هذه الصفحة خاصة  باضافة مشرف</p>
				</div>
			</div>
			</div>
		</div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
	function ChooseAvatar(){$("input[name='avatar']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>
@endsection


