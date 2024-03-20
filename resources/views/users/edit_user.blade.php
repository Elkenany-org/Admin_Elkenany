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
              <h6 class="m-0" style="display: inline;">تعديل مشرف <span class="text-primary"> {{$user->name}} </span></h6>
            </div>
				<div class="row">
					{{-- user info --}}
					<div class="col-sm-3" style="margin-top: 15px">
						<div class="card card-primary card-outline">
			              <div class="card-body box-profile">
			                <div class="text-center">
			                  <img class="profile-user-img img-fluid img-circle" src="{{asset('uploads/users/avatar/'.$user->avatar)}}" alt="User profile picture">
			                </div>

			                <h3 class="profile-username text-center">{{$user->name}}</h3>

			                <p class="text-muted text-center">@if($user->Role){{$user->Role->role}}@else بدون @endif</p>

			                <ul class="list-group list-group-unbordered mb-3">
			                  <li class="list-group-item">
			                    <b>تاريخ التسجيل</b> <a class="float-right text-primary">{{Date::parse($user->created_at)->diffForHumans()}}</a>
			                  </li>
			                  <li class="list-group-item">
			                    <b>اخر تحديث</b> <a class="float-right text-primary">{{Date::parse($user->updated_at)->diffForHumans()}}</a>
			                  </li>

			                </ul>
			              </div>
			              <!-- /.card-body -->
			            </div>
					</div>

					<div class="col-sm-9">
					    <div class="card-body">
			            	<form action="{{route('updateuser')}}" method="post" enctype="multipart/form-data">
				            	<div class="row">
			            			{{csrf_field()}}
			            			<input type="hidden" name="id" value="{{$user->id}}">
				            		{{-- avatar --}}
				            		<div class="col-sm-3" style="margin-bottom: 20px">
				            			<div class="from-group ">
				            				<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
				            				<input type="file" name="avatar" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
				            				<img src="{{asset('uploads/users/avatar/'.$user->avatar)}}" onclick="ChooseAvatar()" id="avatar">
				            			</div>
				            		</div>

				            		{{-- details --}}
				            		<div class="col-sm-9">
				            			<div class="row">
				            				{{-- name --}}
				            				<div class="col-sm-12">
				            					<div class="from-group">
				            						<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
				            						<input type="text" name="name" class="form-control" value="{{$user->name}}" placeholder="إسم المستخدم " required="">
				            					</div>
				            				</div>

				            				{{-- email --}}
				            				<div class="col-sm-6" style="margin-top: 10px">
				                				<div class="from-group">
				            						<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
				            						<input type="email" name="email" class="form-control" value="{{$user->email}}" placeholder="البريد الإلكتروني" required="">
				            					</div>
				            				</div>

				            				{{-- password --}}
				            				<div class="col-sm-6" style="margin-top: 10px">
				                				<div class="from-group">
				            						<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
				            						<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" >
				            					</div>
				            				</div>

				            				{{-- active --}}
				            				<div class="col-sm-6" style="margin-top: 10px">
				                				<div class="from-group">
				            						<label class="text-primary">الحالة : <span class="text-danger">*</span></label>
				            						<select class="form-control" name="active" id="active">
				            							<option value="1">نشط</option>
				            							<option value="0">حظر</option>
				            						</select>
				            					</div>
				            				</div>

				            				{{-- permission --}}
				            				<div class="col-sm-6" style="margin-top: 10px">
				                				<div class="from-group">
				            						<label class="text-primary">الصلاحية : <span class="text-danger">*</span></label>
				            						<select class="form-control" name="role" id="role">
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
          </div>
        </div>
    </div>
@endsection

@section('script')
<script type="text/javascript">

	// active check
	var active = "{{$user->active}}"
	$('#active option').each(function(){
		if($(this).val() == active)
		{
			$(this).attr('selected','')
		}
	});

	// role check
	var role   = "{{$user->role}}"
	$('#role option').each(function(){
		if($(this).val() == role)
		{
			$(this).attr('selected','')
		}
	});

	//choose avatar
	function ChooseAvatar(){$("input[name='avatar']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>
@endsection


