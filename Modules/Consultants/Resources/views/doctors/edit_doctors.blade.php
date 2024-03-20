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
@section('content')
<div class="container-fluid">
	<div class="card card-primary card-outline">

		<div class="card-body">
			<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

				{{-- edit --}}
				<li class="nav-item">
					<a class="nav-link active" id="custom-content-below-doctors-tab" data-toggle="pill" href="#custom-content-below-doctors" role="tab" aria-controls="custom-content-below-doctors" aria-selected="true">بيانات الاستشاري</a>
				</li>

				{{-- servies --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-servies-tab" data-toggle="pill" href="#custom-content-below-servies" role="tab" aria-controls="custom-content-below-servies" aria-selected="false"> الخدمات</a>
				</li>

				{{-- links --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-links-tab" data-toggle="pill" href="#custom-content-below-links" role="tab" aria-controls="custom-content-below-links" aria-selected="false"> الروابط التعريفية</a>
				</li>
				
			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">

			{{-- edit --}}
			<div class="tab-pane fade show active"  id="custom-content-below-doctors" role="tabpanel" aria-labelledby="custom-content-below-doctors-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
							<h6 class="m-0" style="display: inline;">تعديل إستشاري <span class="text-primary"> {{$doctor->name}} </span></h6>
						</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="card-body">
										<form action="{{route('Updatedoctor')}}" method="post" enctype="multipart/form-data">
											<div class="row">
												{{csrf_field()}}
												<input type="hidden" name="id" value="{{$doctor->id}}">
												
												{{-- avatar --}}
												<div class="col-sm-3" style="margin-bottom: 20px">
													<div class="from-group ">
														<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
														<input type="file" name="avatar" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
														<img src="{{asset('uploads/doctors/avatar/'.$doctor->avatar)}}" onclick="ChooseAvatar()" id="avatar">
													</div>
												</div>
												{{-- details --}}
												<div class="col-sm-9">
													<div class="row">
														
														{{-- name --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
																<input type="text" name="name" class="form-control" value="{{$doctor->name}}" placeholder="إسم المستخدم " required="">
															</div>
														</div>
														
														{{-- email --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
																<input type="email" name="email" class="form-control" value="{{$doctor->email}}" placeholder="البريد الإلكتروني" required="">
															</div>
														</div>

														{{-- phone --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
																<input type="text" name="phone" class="form-control" value="{{$doctor->phone}}" placeholder="رقم الهاتف " required="">
															</div>
														</div>

														{{-- address --}}
														<div class="col-sm-8" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary"> العنوان : <span class="text-danger">*</span></label>
																<input type="text" name="address" class="form-control" value="{{$doctor->adress}}" placeholder=" العنوان" required="">
															</div>
														</div>

														{{-- call_duration --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">وقت المكالمة : <span class="text-danger">*</span></label>
																<input type="text" name="call_duration" class="form-control" value="{{$doctor->call_duration}}" placeholder="وقت المكالمة" required="">
															</div>
														</div>

														{{-- call_price --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">سعر المكالمة : <span class="text-danger">*</span></label>
																<input type="number" name="call_price" class="form-control" value="{{$doctor->call_price}}" placeholder="سعر المكالمة" required="">
															</div>
														</div>

														{{-- online_duration --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">وقت الفديو : <span class="text-danger">*</span></label>
																<input type="text" name="online_duration" class="form-control" value="{{$doctor->online_duration}}" placeholder="وقت الفديو" required="">
															</div>
														</div>

														{{-- online_price --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">سعر الفديو : <span class="text-danger">*</span></label>
																<input type="number" name="online_price" class="form-control" value="{{$doctor->online_price}}" placeholder="سعر الفديو" required="">
															</div>
														</div>

														{{-- meeting_duration --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">وقت المقابلة : <span class="text-danger">*</span></label>
																<input type="text" name="meeting_duration" class="form-control" value="{{$doctor->meeting_duration}}" placeholder="وقت المقابلة" required="">
															</div>
														</div>

														{{-- meeting_price --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">سعر المقابلة : <span class="text-danger">*</span></label>
																<input type="number" name="meeting_price" class="form-control" value="{{$doctor->meeting_price}}" placeholder="سعر المقابلة" required="">
															</div>
														</div>
														{{-- password --}}
														<div class="col-sm-4" style="margin-top: 10px">
															<div class="from-group">
																<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
																<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" >
															</div>
														</div>

														{{-- certificates --}}
														<div class="col-sm-6" style="margin-top: 10px">
															<label class="text-primary"> الشهادات <span class="text-danger">*</span></label>
															<textarea class="form-control" rows="5" name="certificates" value="{{$doctor->certificates}}" placeholder="الشهادات" required>{{$doctor->certificates}}</textarea>
														</div>

														{{-- experiences --}}
														<div class="col-sm-6" style="margin-top: 10px">
															<label class="text-primary">الخبرات <span class="text-danger">*</span></label>
															<textarea class="form-control" rows="5" name="experiences" value="{{$doctor->experiences}}" placeholder="الخبرات" required>{{$doctor->experiences}}</textarea>
														</div>


														

														{{-- majors --}}
														<div class="col-sm-12" style="margin-top: 10px">
															<label class="text-primary">التخصصات</label> <span class="text-danger">*</span><br>
															<div class="row">
																@foreach($majors as $value)
																	<div class="col-sm-4">
																		@if(in_array($value->id, $majs))
																			<input type="checkbox"  class="major" checked name="Majors[]" value="{{ $value->id }}"><span style="margin-right: 3px">{{ $value->name }}</span>
																		@else
																			<input type="checkbox"  class="major" name="Majors[]" value="{{ $value->id }}"><span style="margin-right: 3px">{{ $value->name }}</span>
																		@endif
																	</div>
																@endforeach
															</div>
														</div>

														{{-- sections --}}
														<div class="col-sm-12" style="margin-top: 10px">
															<label class="text-primary">الاقسام الخاصة بالتخصصات</label> <span class="text-danger">*</span><br>
															<div class="row sub_sections">
																@foreach($sections as $value)
																	<div class="col-sm-4  sub{{$value->major_id}}">
																		@if(in_array($value->id, $secs))
																			<input type="checkbox" checked name="SubSections[]" value="{{ $value->id }}"><span style="margin-right: 3px">{{ $value->name }}</span>
																		@endif
																	</div>
																@endforeach
															</div>
														</div>
														
														
													</div>
													{{-- submit --}}
														<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-primary btn-block">حفظ</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						
					</div>
				</div>
			</div>

			{{-- servies --}}
			<div class="tab-pane fade" id="custom-content-below-servies" role="tabpanel" aria-labelledby="custom-content-below-servies-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
						<h5 class="m-0" style="display: inline;" style="float: right">وقت الخدمات</h5>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
								إضافة خدمة 
								<i class="fas fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>نوع الخدمة</th>
								<th>التاريخ</th>
								<th>الوقت من</th>
								<th>الوقت الي</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($doctor->DoctorServices as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td>{{$value->services_type}}</td>
									<td> {{$value->date}}</td>
									<td> {{$value->time_from}}</td>
									<td> {{$value->time_to}}</td>
									<td>
										<a href="" 
										class="btn btn-info btn-sm edit"
										data-toggle="modal"
										data-target="#modal_edit"
										data-id    = "{{$value->id}}"
										data-type  = "{{$value->services_type}}"
										data-from = "{{$value->time_from}}"
										data-to = "{{$value->time_to}}"
										data-date = "{{$value->date}}"
										>  تعديل <i class="fas fa-edit"></i></a>
										<form action="{{route('Deleteservies')}}" method="post" style="display: inline-block;">
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
					</div>
					{{-- edit servies modal --}}
					<div class="modal fade" id="modal_edit">
						<div class="modal-dialog">
						<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title"> تعديل </h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('updateservies')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="ser_id" value="">
									<label>الوقت من </label> <span class="text-danger">*</span>
									<input type="time" name="edit_time_from" class="form-control" placeholder="الوقت من " required="" style="margin-bottom: 10px">
									<label>الوقت الي </label> <span class="text-danger">*</span>
									<input type="time" name="edit_time_to" class="form-control" placeholder="الوقت الي " required="" style="margin-bottom: 10px">
									<label> التاريخ </label> <span class="text-danger">*</span>
									<input type="date" name="edit_date" class="form-control" placeholder="التاريخ " required="" style="margin-bottom: 10px">
									<label> النوع </label> <span class="text-danger">*</span>
									<select name="edit_services_type" class="form-control">
												<option value="call">call</option>
												<option value="online">online</option>
												<option value="meeting">meeting</option>
									</select>
									
									<button type="submit"  class="update" id="submit" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light upd">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>

					{{-- add servies modal --}}
					<div class="modal fade" id="modal-primary">
						<div class="modal-dialog">
						<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة وقت جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('Storeservies')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="doctor_id" value="{{$doctor->id}}">
									<label>الوقت من </label> <span class="text-danger">*</span>
									<input type="time" name="time_from" class="form-control" placeholder="الوقت من " required="" style="margin-bottom: 10px">
									<label>الوقت الي </label> <span class="text-danger">*</span>
									<input type="time" name="time_to" class="form-control" placeholder="الوقت الي " required="" style="margin-bottom: 10px">
									<label> التاريخ </label> <span class="text-danger">*</span>
									<input type="date" name="date" class="form-control" placeholder="التاريخ " required="" style="margin-bottom: 10px">
									<label> النوع </label> <span class="text-danger">*</span>
									<select name="services_type" class="form-control">
												<option value="call">call</option>
												<option value="online">online</option>
												<option value="meeting">meeting</option>
									</select>
									<button type="submit" class="submit" id="submit" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light save">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>



			{{-- links --}}
			<div class="tab-pane fade" id="custom-content-below-links" role="tabpanel" aria-labelledby="custom-content-below-links-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
						<h5 class="m-0" style="display: inline;" style="float: right"> الروابط التعريفية</h5>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1" style="float: left;">
								إضافة رابط 
								<i class="fas fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th> الصورة</th>
								<th> العنوان</th>
								<th>التاريخ</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($doctor->DoctorLinks as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/doctors/image/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
									<td> {{$value->title}}</td>
									<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
									<td>
										<a href="" 
										class="btn btn-info btn-sm edit1"
										data-toggle="modal"
										data-target="#modal_edit1"
										data-id    = "{{$value->id}}"
										data-title  = "{{$value->title}}"
										data-link = "{{$value->link}}"
										data-image = "{{$value->image}}"
										>  تعديل <i class="fas fa-edit"></i></a>
										<form action="{{route('Deletelink')}}" method="post" style="display: inline-block;">
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
					</div>
					{{-- edit link  --}}
					<div class="modal fade" id="modal_edit1">
						<div class="modal-dialog">
						<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title"> تعديل </h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('updatelink')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="link_id" value="">
									<label> العنوان</label> <span class="text-danger">*</span>
                    				<input type="text" name="edit_title" class="form-control" placeholder=" العنوان " required="" style="margin-bottom: 10px">
									<label> الرابط</label> <span class="text-danger">*</span>
                    				<input type="text" name="edit_link" class="form-control" placeholder=" الرابط " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;display: block;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="edit_image" accept="image/*" onchange="loadAvatar1(event)" style="display: none;">
									<img src="" class="test" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar1()" id="avatar1">
									
									<button type="submit"  class="update1" id="submit" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light upd1">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>

					{{-- add link --}}
					<div class="modal fade" id="modal-primary1">
						<div class="modal-dialog">
						<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة رابط جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('Storelinks')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="doctor_id" value="{{$doctor->id}}">
									<label> العنوان</label> <span class="text-danger">*</span>
                    				<input type="text" name="title" class="form-control" placeholder=" العنوان " required="" style="margin-bottom: 10px">
									<label> الرابط</label> <span class="text-danger">*</span>
                    				<input type="text" name="link" class="form-control" placeholder=" الرابط " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;display: block;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="image" accept="image/*" onchange="loadAvatar2(event)" style="display: none;">
									<img src="{{asset('dist/img/placeholder.png')}}" style="display: block;max-width: 100%;max-height: 300px;cursor: pointer;" onclick="ChooseAvatar2()" id="avatar2">
									
									<button type="submit" class="submit1" id="submit" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light save1">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">


//choose avatar
function ChooseAvatar(){$("input[name='avatar']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar1(){$("input[name='edit_image']").click()}
var loadAvatar1 = function(event) {
var output = document.getElementById('avatar1');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar2(){$("input[name='image']").click()}
var loadAvatar2 = function(event) {
var output = document.getElementById('avatar2');
output.src = URL.createObjectURL(event.target.files[0]);
};


//edit product
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var type       = $(this).data('type')
	var from      = $(this).data('from')
	var to       = $(this).data('to')
	var date      = $(this).data('date')
	
	$("input[name='ser_id']").val(id)
	$("input[name='edit_time_to']").val(to)
	$("input[name='edit_time_from']").val(from)
	$("input[name='edit_date']").val(date)

	$("select[name='edit_services_type'] > option").each(function() {
            if($(this).val() == type)
            {
              $(this).attr("selected","")
            }
          });


})

//edit link
$('.edit1').on('click',function(){
	var id         = $(this).data('id')
	var title       = $(this).data('title')
	var link      = $(this).data('link')
	var image       = $(this).data('image')
	
	$("input[name='link_id']").val(id)
	$("input[name='edit_title']").val(title)
	$("input[name='edit_link']").val(link)

	var url =  '{{ url("uploads/doctors/image/") }}/' + image
    $('.test').attr('src',url);

})


$('.upd').on('click',function(){
	$('.update').click();
})

$('.save').on('click',function(){
	$('.submit').click();
})

$('.upd1').on('click',function(){
	$('.update1').click();
})

$('.save1').on('click',function(){
	$('.submit1').click();
})

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


