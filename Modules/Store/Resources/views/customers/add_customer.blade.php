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
              <h5 class="m-0" style="display: inline;">إضافة عضو جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('storecustomer')}}" method="post" enctype="multipart/form-data">
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
	            				<div class="col-sm-6" style="margin-top: 10px">
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

	            				{{-- phone --}}
	            				<div class="col-sm-6">
	            					<div class="from-group" style="margin-top: 10px">
	            						<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
	            						<input type="text" name="phone" class="form-control" value="{{old('phone')}}" placeholder="رقم الهاتف " required="">
	            					</div>
	            				</div>
	            				
	            			</div>
	            		</div>
						{{-- submit --}}
	            			<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
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
			<p>هذه الصفحة خاصة   بإضافة عضو</p>
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


