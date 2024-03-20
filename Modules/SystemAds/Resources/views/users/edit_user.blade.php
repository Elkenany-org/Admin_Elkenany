@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 150px;
		height: 117px;
	}
	#avatar:hover{
		width: 150px;
		height: 117px;
		cursor: pointer;
	}

	.result_style{
		border: 1px solid #12a3b8;
		padding-top: 12px;
		border-radius: 22px;
	}

</style>


@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
			
			<div class="card-body" style="padding-top: 10px">
				<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            		{{-- user info --}}
					<li class="nav-item">
						<a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-setting" role="tab" aria-controls="custom-content-below-home" aria-selected="true">المعلومات الأساسيه</a>
					</li>
            		{{-- membership  --}}
					<li class="nav-item">
						<a class="nav-link" id="custom-content-below-membership-tab" data-toggle="pill" href="#custom-content-below-membership" role="tab" aria-controls="custom-content-below-membership" aria-selected="true"> اضافة عضوية</a>
				 	</li>

					 {{-- allmem  --}}
					<li class="nav-item">
						<a class="nav-link" id="custom-content-below-allmem-tab" data-toggle="pill" href="#custom-content-below-allmem" role="tab" aria-controls="custom-content-below-allmem" aria-selected="true">  العضويات</a>
				 	</li>
					 
				</ul>

				<div class="tab-content" id="custom-content-below-tabContent">
					{{-- user info --}}
					<div class="tab-pane fade show active" id="custom-content-below-setting" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
						<div class="row">
							
			
							<div class="col-sm-12">
								<div class="card-body">
									<form action="{{route('updateuserads')}}" method="post" enctype="multipart/form-data">
										<div class="row">
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$user->id}}">
										
											{{-- details --}}
											<div class="col-sm-12">
												<div class="row">
													{{-- name --}}
													<div class="col-sm-6">
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
													
												</div>
											</div>
								
											<div class="col-sm-12">
												<div class="row">
													{{-- password --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
															<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" >
														</div>
													</div>
													{{-- phone --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
															<input type="text" name="phone" class="form-control" value="{{$user->phone}}" placeholder="رقم الهاتف " required="">
														</div>
													</div>
													<div class="col-sm-6 comp"  style="margin-top: 10px;">
														<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
														<input type="search" class="form-control company_search" name="company_search">
													</div>

													<div class="col-sm-12 " style="margin-top:20px">
														<div class="row company_search_result">

														@foreach($user->AdsCompanys as $value)
															<div class="col-sm-2 result_style">
																
																	<input  style="display: inline;width: 50%;" type="checkbox" checked name="company_id[]" value="{{ $value->Company->id }}">
																	<label class="text-info">{{ $value->Company->name }}</label>
																
															</div>

														@endforeach
														</div>
													</div>
												</div>
											</div>
										</div>
										{{-- submit --}}
										<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px; " class="btn btn-outline-primary btn-block">حفظ</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					{{-- data analysis --}}
					<div class="tab-pane fade show" id="custom-content-below-membership" role="tabpanel" aria-labelledby="custom-content-below-membership-tab">
					
						<ul class="nav nav-pills " style="margin-top:30px;" id="custom-content-below-tab" role="tablist">
							@foreach($user->AdsCompanys as $value)
								{{-- user AdsCompanys --}}
								<li class="nav-item">
									<a class="nav-link" id="custom-content-below-{{ $value->Company->id }}-tab" data-toggle="pill" href="#custom-content-below-{{ $value->Company->id }}" role="tab" aria-controls="custom-content-below-{{ $value->Company->id }}" aria-selected="true"> {{ $value->Company->name }}</a>
								</li>
							@endforeach	
							
						</ul>
						<div class="tab-content" id="custom-content-below-tabContent">
						    @foreach($user->AdsCompanys as $value)
								{{-- user info --}}
								<div class="tab-pane fade " id="custom-content-below-{{ $value->Company->id }}" role="tabpanel" aria-labelledby="custom-content-below-{{ $value->Company->id }}-tab">
									<form action="{{route('Storemembershipads')}}" method="post" enctype="multipart/form-data">
										<div class="row">
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$user->id}}">
											<input type="hidden" name="company_id" value="{{ $value->Company->id }}">

											{{-- details --}}
											<div class="col-sm-12">
												<div class="row">
													{{-- ads_count --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary"> العدد : <span class="text-danger">*</span></label>
															<input type="number" name="ads_count" class="form-control" value="{{old('ads_count')}}" placeholder=" العدد" required="">
														</div>
													</div>

													{{-- main --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary"> عدد الرئيسي : <span class="text-danger">*</span></label>
															<input type="number" name="main" class="form-control" value="{{old('main')}}" placeholder=" عدد الرئيسي">
														</div>
													</div>

													{{-- sub --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary"> عدد الفرعي : <span class="text-danger">*</span></label>
															<input type="number" name="sub" class="form-control" value="{{old('sub')}}" placeholder=" عدد الفرعي">
														</div>
													</div>

													{{-- type --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
														<label class="text-primary">  النوع</label> <span class="text-danger">*</span>
														<select name="type" class="form-control">
																<option value="" selected disabled>اختار</option>
																<option value="banner">banner</option>
																<option value="logo">logo</option>
																<option value="sort">sort</option>
																<option value="popup">popup</option>
																<option value="notification">notification</option>
														</select>
														</div>
													</div>

													{{-- start_date --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary"> تاريخ الابتداء : <span class="text-danger">*</span></label>
															<input type="date" name="start_date" class="form-control" min="{{date('Y-m-d')}}" value="{{old('start_date')}}" placeholder=" تاريخ الابتداء" required="">
														</div>
													</div>

													{{-- end_date --}}
													<div class="col-sm-6" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary"> تاريخ الانتهاء : <span class="text-danger">*</span></label>
															<input type="date" name="end_date" class="form-control" min="{{date('Y-m-d')}}" value="{{old('end_date')}}" placeholder=" تاريخ الانتهاء" required="">
														</div>
													</div>

												</div>
											</div>
											{{-- submit --}}
												<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
										</div>
									</form>
								</div>
							@endforeach	
						</div>
					
					</div>
					<div class="tab-pane fade show" id="custom-content-below-allmem" role="tabpanel" aria-labelledby="custom-content-below-allmem-tab">
				
					<div class="row">
						<div class="col-sm-12">
						<div class="card card-primary card-outline">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;">قائمة  العضويات </h5>
							</div>
							<!-- /.card-header -->
							<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>الإسم</th>
								<th> النوع</th>
								<th> العدد</th>
								<th>تاريخ الانتهاء</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($user->Memberships as $key => $value)
									<tr>
										<td>{{$key+1}}</td>
										<td>{{$value->Company->name}}</td>
										<td>{{$value->type}}</td>
										<td>{{$value->ads_count}}</td>
										<td>{{$value->end_date}}</td>

										<td>
											<a href="{{url('/all-system-ads?user_id='.$value->ads_user_id.'&type='.$value->type)}}" class="btn btn-info btn-sm">عرض <i class="fas fa-eye"></i></a>
											<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalEdit{{$value->id}}" data-whatever="@mdo"> تعديل <i class="fas fa-edit"></i></button>
											<form action="{{route('Deletmembershipads')}}" method="post" style="display: inline-block;">
												{{csrf_field()}}
												<input type="hidden" name="id" value="{{$value->id}}">
												<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
											</form>
										</td>
									</tr>
									<div class="modal fade" id="exampleModalEdit{{$value->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<form action="{{route('Updatemembership',$value->id)}}" method="POST">
													@csrf
													<div class="modal-header">
														<h5 class="modal-title" id="exampleModalLabel">النوع: {{$value->type}}  </h5>
														<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
														</button>
													</div>
													<div class="modal-body">
														<div class="form-group row">
															<div class="col-md-6">
																<label  class="col-form-label">العدد:</label>
																<input type="number" name="ads_count" id="total" class="form-control" value="{{$value->ads_count}}">
															</div>
														</div>
														<div class="form-group row">
															<div class="col-md-6">
																<label for="recipient-name" class="col-form-label">العدد الرئيسي:</label>
																<input type="number" name="main" min="0" id="main" class="form-control" value="{{$value->main}}">
															</div>
															<div class="col-md-6">
																<label for="recipient-name" class="col-form-label">العدد الفرعي:</label>
																<input type="number" name="sub" min="0" id="sub" class="form-control" value="{{$value->sub}}">
															</div>

														</div>
														<div class="form-group row">
															<div class="col-md-6">
																<label for="recipient-name" class="col-form-label">تاريخ الابتداء:</label>
																<input type="date" name="start_date" class="form-control" min="{{$value->start_date}}" value="{{$value->start_date}}">
															</div>
															<div class="col-md-6">
																<label for="recipient-name" class="col-form-label">تاريخ الانتهاء:</label>
																<input type="date" name="end_date" class="form-control" value="{{$value->end_date}}">
															</div>

														</div>
														<div>
															<span class="text-danger" id="error-msg" style="display: none">يجب ان يكون مجموع العدد الرئيسي يساوي العدد الفرعي*</span>
														</div>
													</div>
													<div class="modal-footer">
														<button type="button" class="btn btn-secondary" data-dismiss="modal" style="margin-left: auto">إغلاق</button>
														<button type="submit" id="editbtn_submit" class="btn btn-primary">تعديل</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								@endforeach
								</tfoot>
							</table>
							</div>
							<!-- /.card-body -->
						</div>
									
					</div>
				</div>
			</div>
          </div>
        </div>
    </div>
@endsection

@section('script')

	<script>
		var total = parseInt($('#total').val());
		var main = parseInt($('#main').val());
		var sub = parseInt($('#sub').val());
		var sum = parseInt(main+sub);
		var validate = true;
		$('#total').change(function (){
			total = parseInt(this.value);
		});
		$('#main').change(function (){
			main = parseInt(this.value);
			sub = parseInt(sub);
			sum = parseInt(main+sub);
		});
		$('#sub').change(function (){
			sub = parseInt(this.value);
			main = parseInt(main);
			sum = parseInt(main+sub);
		});

		$('#editbtn_submit').click(function (){
			console.log(' total =>'+total);
			if(sum != total){
				$('#error-msg').show();
				validate = false;
			}
			if (sum == total){
				validate = true;
			}


			return validate;
		})
	</script>


<script type="text/javascript">
	//choose avatar
	function ChooseAvatar(){$("input[name='avatar']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};

	$('.save').on('click',function(){
	$('.submit').click();
})

	
		// search companies
$(document).on('keyup','.company_search', function(){

var data = {
	search     : $(this).val(),
	_token     : $("input[name='_token']").val()
}

$.ajax({
url     : "{{ url('get-companies-search-ads') }}",
method  : 'post',
data    : data,
success : function(s,result){
	if ($('input[name="company_id[]"]').is(':checked')) { 
        $('input[name="company_id[]"]').not(':checked').parent().fadeOut().remove();
			$.each(s,function(k,v){
				if ($('input[name="company_id[]"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="checkbox" value="${v.id}"  class="company_id" name="company_id[]">
							<label class="text-info">${v.name}</label>
						</div>
					`);
				}
		})
	}else{
		$('.company_search_result').html('')
		$.each(s,function(k,v){
				if ($('input[name="company_id[]"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="checkbox" value="${v.id}"  class="company_id" name="company_id[]">
							<label class="text-info">${v.name}</label>
						</div>
					`);
				}
		})
	}
}});

});
</script>
@endsection


