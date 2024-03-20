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
              <h5 class="m-0" style="display: inline;">إضافة إستشاري جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('Storedoctor')}}" method="post" enctype="multipart/form-data">
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
	            				<div class="col-sm-4">
	            					<div class="from-group">
	            						<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
	            						<input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="إسم المستخدم " required="">
	            					</div>
	            				</div>

	            				{{-- email --}}
	            				<div class="col-sm-4">
	                				<div class="from-group">
	            						<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
	            						<input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="البريد الإلكتروني" required="">
	            					</div>
	            				</div>

	            				{{-- phone --}}
	            				<div class="col-sm-4">
	            					<div class="from-group">
	            						<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
	            						<input type="text" name="phone" class="form-control" value="{{old('phone')}}" placeholder="رقم الهاتف " required="">
	            					</div>
	            				</div>

								{{-- address --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary"> العنوان : <span class="text-danger">*</span></label>
	            						<input type="text" name="address" class="form-control" value="{{old('address')}}" placeholder=" العنوان" required="">
	            					</div>
	            				</div>

								{{-- call_duration --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary">وقت المكالمة : <span class="text-danger">*</span></label>
	            						<input type="text" name="call_duration" class="form-control" value="{{old('call_duration')}}" placeholder="وقت المكالمة" required="">
	            					</div>
	            				</div>

								{{-- call_price --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">سعر المكالمة : <span class="text-danger">*</span></label>
	            						<input type="number" name="call_price" class="form-control" value="{{old('call_price')}}" placeholder="سعر المكالمة" required="">
	            					</div>
	            				</div>

								{{-- online_duration --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary">وقت الفديو : <span class="text-danger">*</span></label>
	            						<input type="text" name="online_duration" class="form-control" value="{{old('online_duration')}}" placeholder="وقت الفديو" required="">
	            					</div>
	            				</div>

								{{-- online_price --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">سعر الفديو : <span class="text-danger">*</span></label>
	            						<input type="number" name="online_price" class="form-control" value="{{old('online_price')}}" placeholder="سعر الفديو" required="">
	            					</div>
	            				</div>

								{{-- meeting_duration --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary">وقت المقابلة : <span class="text-danger">*</span></label>
	            						<input type="text" name="meeting_duration" class="form-control" value="{{old('meeting_duration')}}" placeholder="وقت المقابلة" required="">
	            					</div>
	            				</div>

								{{-- meeting_price --}}
	            				<div class="col-sm-4" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">سعر المقابلة : <span class="text-danger">*</span></label>
	            						<input type="number" name="meeting_price" class="form-control" value="{{old('meeting_price')}}" placeholder="سعر المقابلة" required="">
	            					</div>
	            				</div>

								{{-- certificates --}}
								<div class="col-sm-6" style="margin-top: 10px">
									<label class="text-primary">الشهادات <span class="text-danger">*</span></label>
									<textarea class="form-control" rows="4" name="certificates" value="{{old('certificates')}}" placeholder="الشهادات" required></textarea>
								</div>

								{{-- experiences --}}
								<div class="col-sm-6" style="margin-top: 10px">
									<label class="text-primary">الخبرات <span class="text-danger">*</span></label>
									<textarea class="form-control" rows="4" name="experiences" value="{{old('experiences')}}" placeholder="الخبرات" required></textarea>
								</div>

								{{-- majors --}}
	            				<div class="col-sm-12" style="margin-top: 10px">
									<label class="text-primary">التخصصات</label> <span class="text-danger">*</span>
									<div class="row">
										@foreach($majors as $value)
											<div class="col-sm-4">
												<input type="checkbox" class="major" name="Majors[]" value="{{ $value->id }}">
												<span style="margin-right: 3px">{{ $value->name }}</span>
											</div>
										@endforeach
									</div>
	            				</div>

								{{-- sections --}}
	            				<div class="col-sm-12" style="margin-top: 10px">
									<label class="text-primary">الاقسام الخاصة بالتخصصات</label> <span class="text-danger">*</span>
									<div class="row sub_sections">

									</div>
	            				</div>

								{{--warning--}}
								<div class="modal fade" id="modal-secondary">
									<div class="modal-dialog">
									<div class="modal-content bg-secondary">
									<div class="modal-body">
									<p>هذه الصفحة خاصة  باضافة استشاري</p>
									</div>
									</div>
									</div>
								</div>

								{{-- password --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
	            						<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" required="">
	            					</div>
	            				</div>
	            			</div>
	            		</div>
						{{-- submit --}}
	            		<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px; " type="submit" class="btn btn-outline-primary btn-block">حفظ</button>
	            	</div>
            	</form>
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

// get sub sections
$(document).on('change','.major', function(){
	var id = $(this).val();
		if ($(this).is(':checked')) {
			
	var data = {
	major_id    : $(this).val(),
	_token        : $("input[name='_token']").val()
	}


		$.ajax({
		url     : "{{ url('get-sub-sections-to-doctors') }}",
		method  : 'post',
		data    : data,
		success : function(s,result){
			
    
			$.each(s,function(k,v){

			$('.sub_sections').append(`

				<div class="col-sm-4 sub${id}">
					<input type="checkbox" name="SubSections[]" value="${v.id}">
					<span style="margin-right: 3px">${v.name}</span>
				</div>
			`);
		})
	
		}});
	}else{

		$('.sub'+id).fadeOut().remove();
	}
	});
</script>
@endsection


