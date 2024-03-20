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

</style>


@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
			
			<div class="card-body" style="padding-top: 10px">
				<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
            		{{-- customer info --}}
					<li class="nav-item">
						<a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-setting" role="tab" aria-controls="custom-content-below-home" aria-selected="true">المعلومات الأساسيه</a>
					</li>
            		{{-- data analysis --}}
					<li class="nav-item">
						<a class="nav-link" id="custom-content-below-data-tab" data-toggle="pill" href="#custom-content-below-data" role="tab" aria-controls="custom-content-below-data" aria-selected="true">إجمالي الإحصائيات</a>
				 	</li>
				</ul>

				<div class="tab-content" id="custom-content-below-tabContent">
					{{-- customer info --}}
					<div class="tab-pane fade show active" id="custom-content-below-setting" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
						<div class="row">
							<div class="col-sm-3" style="margin-top: 15px">
								<div class="card card-primary card-outline">
									<div class="card-body box-profile">
									<div class="text-center">
										<img class="profile-user-img img-fluid img-circle" src="{{asset('uploads/customers/avatar/'.$customer->avatar)}}" alt="User profile picture">
									</div>
			
									<h3 class="profile-username text-center">{{$customer->name}}</h3>
			
									<ul class="list-group list-group-unbordered mb-3">
										<li class="list-group-item">
										<b>تاريخ التسجيل</b> <a class="float-right text-primary">{{Date::parse($customer->created_at)->diffForHumans()}}</a>
										</li>
										<li class="list-group-item">
										<b>اخر تحديث</b> <a class="float-right text-primary">{{Date::parse($customer->updated_at)->diffForHumans()}}</a>
										</li>
			
									</ul>
									</div>
									<!-- /.card-body -->
								</div>
							</div>
			
							<div class="col-sm-9">
								<div class="card-body">
									<form action="{{route('updatecustomer')}}" method="post" enctype="multipart/form-data">
										<div class="row">
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$customer->id}}">
											{{-- avatar --}}
											<div class="col-sm-3" style="margin-bottom: 10px">
												<div class="from-group ">
													<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
													<input type="file" name="avatar" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
													<img src="{{asset('uploads/customers/avatar/'.$customer->avatar)}}" onclick="ChooseAvatar()" id="avatar">
												</div>
											</div>
			
											{{-- details --}}
											<div class="col-sm-9">
												<div class="row">
													{{-- name --}}
													<div class="col-sm-12">
														<div class="from-group">
															<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
															<input type="text" name="name" class="form-control" value="{{$customer->name}}" placeholder="إسم المستخدم " required="">
														</div>
													</div>
			
													{{-- email --}}
													<div class="col-sm-12" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
															<input type="email" name="email" class="form-control" value="{{$customer->email}}" placeholder="البريد الإلكتروني" required="">
														</div>
													</div>
													
												</div>
											</div>
								
											<div class="col-sm-12">
												<div class="row">
													{{-- password --}}
													<div class="col-sm-12" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
															<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" >
														</div>
													</div>
													{{-- phone --}}
													<div class="col-sm-12" style="margin-top: 10px">
														<div class="from-group">
															<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
															<input type="text" name="phone" class="form-control" value="{{$customer->phone}}" placeholder="رقم الهاتف " required="">
														</div>
													</div>

													<div class="custom-control custom-radio col-sm-12" style="margin-top: 10px">
													<input type="radio" id="customRadio1" name="memb" value="0" @if($customer->memb == 0) checked @endif class="custom-control-input">
													<label class="custom-control-label" for="customRadio1">مجاني</label>
													</div>
													<div class="custom-control custom-radio col-sm-12" style="margin-top: 10px">
													<input type="radio" id="customRadio2" name="memb" value="1" @if($customer->memb == 1) checked @endif class="custom-control-input">
													<label class="custom-control-label" for="customRadio2">  مدفوع</label>
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
					<div class="tab-pane fade show" id="custom-content-below-data" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
					
						<div class="row">
							<div class="col-sm-12">
							<div class="card-header">
								<a href="{{route('dataCustomer',$customer->id)}}" class="btn btn-primary" style="float: left;">
									بيانات الاحصائيات
									<i class="fas fa-eye"></i>
								</a>
							</div>
								<!-- /.card-header -->
								<div class="card-body">
								<table id="example1" class="table table-bordered table-hover table-striped">
									<thead>
									<tr>
									<th>#</th>
									<th>الإسم</th>
									<th>keyword</th>
									<th>عدد الإستخدام</th>
									</tr>
									</thead>
									<tbody>
									@foreach($customer->User_Analysis as $key => $value)
										<tr>
										<td>{{$value->Keyword->id}}</td>
										<td>{{$value->Keyword->name}}</td>
										<td>{{$value->Keyword->keyword}}</td>
										<td>{{$value->Keyword->use_count}}</td>
										</tr>
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


	
</script>
@endsection


