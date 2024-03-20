@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100%;
		height:250px;
	}
	#avatar:hover{
		width: 100%;
		height:250px;
		cursor: pointer;
	}
	.img img{
		width:150px;
		height:150px;
		margin-right:20px;
		margin-top:20px;
	}

	#gallery-photo-add {
    display: inline-block;
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 50px;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
	}
</style>
@endsection
@section('content')
<div class="container-fluid">
	<div class="card card-primary card-outline">

		<div class="card-body">
			<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

				{{-- edit --}}
				<li class="nav-item">
					<a class="nav-link active" id="custom-content-below-courses-tab" data-toggle="pill" href="#custom-content-below-courses" role="tab" aria-controls="custom-content-below-courses" aria-selected="true">بيانات الكورس</a>
				</li>

				{{-- add live --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-addlive-tab" data-toggle="pill" href="#custom-content-below-addlive" role="tab" aria-controls="custom-content-below-addlive" aria-selected="false"> اضافة لايف</a>
				</li>

				{{-- live --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-live-tab" data-toggle="pill" href="#custom-content-below-live" role="tab" aria-controls="custom-content-below-live" aria-selected="false"> اللايف</a>
				</li>

				{{-- addmeeting --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-addmeeting-tab" data-toggle="pill" href="#custom-content-below-addmeeting" role="tab" aria-controls="custom-content-below-addmeeting" aria-selected="false"> اضافة مقابلة</a>
				</li> 

				{{-- meeting --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-meeting-tab" data-toggle="pill" href="#custom-content-below-meeting" role="tab" aria-controls="custom-content-below-meeting" aria-selected="false"> الاوفلاين</a>
				</li>

				{{-- videos --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-videos-tab" data-toggle="pill" href="#custom-content-below-videos" role="tab" aria-controls="custom-content-below-videos" aria-selected="false"> الفديوهات</a>
				</li>

				{{-- quizze --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-quizze-tab" data-toggle="pill" href="#custom-content-below-quizze" role="tab" aria-controls="custom-content-below-quizze" aria-selected="false"> الاختبارات</a>
				</li>

				{{-- comments --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-comments-tab" data-toggle="pill" href="#custom-content-below-comments" role="tab" aria-controls="custom-content-below-comments" aria-selected="false"> التعليقات</a>
				</li>

			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">
			{{-- edit --}}
			<div class="tab-pane fade show active" id="custom-content-below-courses" role="tabpanel" aria-labelledby="custom-content-below-courses-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
						<h6 class="m-0" style="display: inline;">تعديل الكورس <span class="text-primary"> {{$courses->title}} </span></h6>
						</div>
						<div class="card-body">
							<form action="{{route('Updatecourses')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$courses->id}}">
									<div class="row">
										{{-- details --}}
										{{-- title --}}
										<div class="col-sm-2" style="margin-top: 10px">
										</div>
										<div class="col-sm-8" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary">عنوان الكورس: <span class="text-danger">*</span></label>
											<input type="text" name="title" class="form-control" value="{{$courses->title}}" placeholder="عنوان الكورس" required="">
										</div>
										</div>
										<div class="col-sm-2" style="margin-top: 10px">
										</div>
										<div class="col-sm-12" style="margin-top: 10px">
											<div class="row">
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> سعر اللايف: <span class="text-danger">*</span></label>
												<input type="number" name="price_live" class="form-control" value="{{$courses->price_live}}" placeholder=" سعر اللايف" >
												</div>
											</div>
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> سعر الاوفلاين: <span class="text-danger">*</span></label>
												<input type="number" name="price_meeting" class="form-control" value="{{$courses->price_meeting}}" placeholder=" سعر الاوفلاين" >
												</div>
											</div>
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> سعر الاونلاين: <span class="text-danger">*</span></label>
												<input type="number" name="price_offline" class="form-control" value="{{$courses->price_offline}}" placeholder=" سعر  الاونلاين" >
												</div>
											</div>
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> عدد ساعات اللايف: <span class="text-danger">*</span></label>
												<input type="number" name="hourse_live" class="form-control" value="{{$courses->hourse_live}}" placeholder=" عدد ساعات اللايف" >
												</div>
											</div>
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> عدد ساعات الاوفلاين: <span class="text-danger">*</span></label>
												<input type="number" name="hourse_meeting" class="form-control" value="{{$courses->hourse_meeting}}" placeholder=" عدد ساعات الاوفلاين" >
												</div>
											</div>
											<div class="col-sm-3" style="margin-top: 10px">
												<div class="from-group">
												<label class="text-primary"> عدد ساعات الاونلاين: <span class="text-danger">*</span></label>
												<input type="number" name="hourse_offline" class="form-control" value="{{$courses->hourse_offline}}" placeholder=" عدد ساعات  الاونلاين" >
												</div>
											</div>
											</div>
										</div>
										<div class="col-sm-3" style="margin-top: 10px">
										</div>
										<div class="col-sm-6 marbo" style="margin-top: 10px">
											<label class="text-primary">إختيار صورة <span class="text-danger"> * </span></label><br>
											<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
											<img src="{{asset('uploads/courses/avatar/'.$courses->image)}}" onclick="ChooseAvatar()" id="avatar">
										</div>
										<div class="col-sm-3" style="margin-top: 10px">
										</div>
										{{-- desc --}}
										<div class="col-sm-12">
											<label class="text-primary">التفاصيل <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="7" name="desc" value="{{$courses->desc}}" placeholder="التفاصيل" required>{{$courses->desc}}</textarea>
										</div>
									</div>
									
									
							{{-- submit --}}
							<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
							</form>
						</div>
					</div>  
				</div>
			</div>

			{{-- addlive --}}
			<div class="tab-pane fade" id="custom-content-below-addlive" role="tabpanel" aria-labelledby="custom-content-below-addlive-tab">
				<div class="card-body">
				<form action="{{route('storelive')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$courses->id}}">
					<div class="row">
						<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> العنوان: <span class="text-primary">*</span></label>
							<input type="text" name="title_live" class="form-control" value="{{old('title_live')}}" placeholder=" العنوان">
						</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
							<div class="from-group">
								<label class="text-primary"> التاريخ: <span class="text-primary">*</span></label>
								<input type="date" name="date_live" class="form-control" value="{{old('date')}}" required placeholder=" التاريخ">
							</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
							<div class="from-group">
								<label class="text-primary"> الوقت: <span class="text-primary">*</span></label>
								<input type="time" name="time_live" class="form-control" value="{{old('time')}}" required placeholder=" الوقت">
							</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
							<div class="from-group">
								<label class="text-primary"> الرابط: <span class="text-primary">*</span></label>
								<input type="text" name="link_live" class="form-control" value="{{old('link')}}" required placeholder=" الرابط">
							</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
							<div class="from-group">
								<label class="text-primary"> اسم التطبيق: <span class="text-primary">*</span></label>
								<input type="text" name="application" class="form-control" value="{{old('application')}}" required placeholder=" التطبيق">
							</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
							<input type="text" name="prof_live" class="form-control" value="{{old('prof_live')}}" placeholder=" المحاضر">
						</div>
						</div>
						<div class="col-sm-4" style="margin-top: 10px">
							<div class="from-group">
								<label class="text-primary"> عدد الساعات: <span class="text-primary">*</span></label>
								<input type="number" name="hourse_count_live" class="form-control" value="{{old('hourse_count')}}" required placeholder=" عدد الساعات">
							</div>
						</div>
					</div>
					{{-- submit --}}
					<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
				</form>
				</div>
			</div>

			{{-- live --}}
			<div class="tab-pane fade" id="custom-content-below-live" role="tabpanel" aria-labelledby="custom-content-below-live-tab">
				<div class="row">
					<div class="col-sm-12">
						<!-- /.card-header -->
						<div class="card-body">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
							<th>#</th>
							<th>الاسم</th>
							<th> التاريخ</th>
							<th> السعر</th>
							<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($courses->CourseLive as $key => $value)
								<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->title}}</td>
								<td>{{$value->date}}</td>
								<td>{{$value->price}}</td>
								<td>
								<a href="{{route('Editlive',$value->id)}}" class="btn btn-primary btn-sm " type="submit"> تعديل <i class="fas fa-edit"></i></a>
								<form action="{{route('Deletelive')}}" method="post" style="display: inline-block;">
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
				</div>
			</div>

			{{-- addmeeting --}}
			<div class="tab-pane fade" id="custom-content-below-addmeeting" role="tabpanel" aria-labelledby="custom-content-below-addmeeting-tab">
				<div class="card-body">
					<form action="{{route('storemeeting')}}" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<input type="hidden" name="id" value="{{$courses->id}}">
						<input type="hidden"  value="" id="latitude" name="latitude">
						<input type="hidden" value="" id="longitude"  name="longitude">
						<div class="row">
							<div class="col-sm-6 marbo" style="margin-top: 15px">
								<label class="text-primary">العنوان:<span class="text-primary">*</span></label>
								<input type="text" id="pac-input" class="form-control" required placeholder="  " name="location">
								<div class="validate-input" id="map" style="min-height: 300px;min-width: 250px;"></div>
							</div>
							<div class="col-sm-6" style="margin-top: 30px">
								<div class="row">
									<div class="col-sm-12" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> العنوان: <span class="text-primary">*</span></label>
										<input type="text" name="title_Meeting" class="form-control" value="{{old('title_Meeting')}}" placeholder=" العنوان">
									</div>
									</div>
									<div class="col-sm-12" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary"> التاريخ: <span class="text-primary">*</span></label>
											<input type="date" name="date_Meeting" required class="form-control" value="{{old('date')}}" placeholder=" التاريخ">
										</div>
									</div>
									<div class="col-sm-12" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary"> الوقت: <span class="text-primary">*</span></label>
											<input type="time" name="time_Meeting" required class="form-control" value="{{old('time')}}" placeholder=" الوقت">
										</div>
									</div>
									<div class="col-sm-12" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
										<input type="text" name="prof_Meeting" class="form-control" value="{{old('prof_Meeting')}}" placeholder=" المحاضر">
									</div>
									</div>
									<div class="col-sm-12" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary"> عدد الساعات: <span class="text-primary">*</span></label>
											<input type="number" name="hourse_count_Meeting" required class="form-control" value="{{old('hourse_count')}}" placeholder=" عدد الساعات">
										</div>
									</div>
								</div>
							</div>
						</div>
						{{-- submit --}}
						<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
					</form>
				</div>
			</div>

			{{-- meeting --}}
			<div class="tab-pane fade" id="custom-content-below-meeting" role="tabpanel" aria-labelledby="custom-content-below-meeting-tab">
				<div class="row">
					<div class="col-sm-12">
						<!-- /.card-header -->
						<div class="card-body">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
							<th>#</th>
							<th>الاسم</th>
							<th> التاريخ</th>
							<th> السعر</th>
							<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($courses->CourseMeeting as $key => $value)
								<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->title}}</td>
								<td>{{$value->date}}</td>
								<td>{{$value->price}}</td>
								<td>
								<a href="{{route('Editmeeting',$value->id)}}" class="btn btn-primary btn-sm " type="submit"> تعديل <i class="fas fa-edit"></i></a>
								<form action="{{route('Deletemeeting')}}" method="post" style="display: inline-block;">
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
				</div>
			</div>

			{{-- offline --}}
			<div class="tab-pane fade"  id="custom-content-below-videos" role="tabpanel" aria-labelledby="custom-content-below-videos-tab">
				@foreach($courses->CourseOffline as $key => $value)	
					<form action="{{route('updateoffline')}}" method="post" enctype="multipart/form-data">
						{{csrf_field()}}
						<div class="card-header">
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
								إضافة مجلد 
								<i class="fas fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
							<input type="hidden" name="oid" value="{{$value->id}}">
							<div class="row">
								<div class="col-sm-8" style="margin-top: 10px">
									<div class="from-group">
									<label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
									<input type="text" name="edit_prof_offline" class="form-control" value="{{$value->prof}}" placeholder=" المحاضر">
									</div>
								</div>
								<div class="col-sm-4" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> عدد الساعات: <span class="text-primary">*</span></label>
										<input type="text" name="edit_hourse_count_offline" class="form-control" value="{{$value->hourse_count}}" placeholder=" عدد الساعات">
									</div>
								</div>
							</div>
							{{-- submit --}}
							<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>

							<div class="gallery">
								@foreach($value->CourseOfflineFolders as $key => $folder)
								<div class="image col-md-3 col-sm-6 col-12"  style="position: relative;display: inline-block;margin-top:30px;" data-category="1" data-sort="white sample">
									<button type="button" data-id="{{$folder->id}}" class="btn btn-danger btn-sm dele close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 5px 10px;color: #fff;border-radius: 50%;top: 0px;right: 0px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<div class="info-box">
									<span class="info-box-icon bg-info"><a href="{{route('showfolder',$folder->id)}}" class="btn btn-sm " style="font-size:40px" type="submit"><i class="fas fa-folder"></i></a></span>

										<div class="info-box-content">
											<span class="info-box-text">{{$folder->name}}</span>
											<a href="" 
											class="btn btn-info btn-sm edit"
											data-toggle="modal"
											data-target="#modal-update"
											data-id    = "{{$folder->id}}"
											data-name  = "{{$folder->name}}"
											>  تعديل <i class="fas fa-edit"></i></a>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</form>

					{{-- add folder modal --}}
					<div class="modal fade" id="modal-primary">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
								<h4 class="modal-title">إضافة مجلد جديد</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
								<form action="{{route('Storefolder')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$value->id}}">
									<input type="hidden" name="courses_id" value="{{$courses->id}}">
									<label>  اسم المجلد</label> <span class="text-danger">*</span>
									<input type="text" name="folder_name" class="form-control" placeholder="  الاسم " required="" style="margin-bottom: 10px"></br>
									<button type="submit" id="submit" style="display: none;"></button>
								</form>
							</div>
							<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-outline-light save">حفظ</button>
								<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
							</div>
						</div>
					</div>

					{{-- edit folder modal --}}
					<div class="modal fade" id="modal-update">
						<div class="modal-dialog">
						<div class="modal-content bg-info">
							<div class="modal-header">
							<h4 class="modal-title">تعديل المجلد : <span class="item_name"></span></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('Updatefolder')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="edit_id" value="{{$value->id}}">
									<label>  اسم المجلد</label> <span class="text-danger">*</span>
									<input type="text" name="edit_folder_name" class="form-control" placeholder="  الاسم " required="" style="margin-bottom: 10px"></br>
									<button type="submit" id="update" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light update">تحديث</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>
				@endforeach
				@if(count($courses->CourseOffline) == 0)
				<form action="{{route('storeoffline')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<div class="card-header">
						
					</div>
					<div class="card-body">
						<input type="hidden" name="id" value="{{$courses->id}}">
						<div class="row">
						<div class="col-sm-8" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
										<input type="text" name="prof_offline" class="form-control" value="{{old('prof_offline')}}" placeholder=" المحاضر">
									</div>
									</div>
							<div class="col-sm-4" style="margin-top: 10px">
								<div class="from-group">
									<label class="text-primary"> عدد الساعات: <span class="text-primary">*</span></label>
									<input type="text" name="hourse_count_offline" class="form-control" value="{{old('hourse_count')}}" placeholder=" عدد الساعات">
								</div>
							</div>
						</div>
						{{-- submit --}}
						<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
					</div>
				</form>
				@endif
			</div>

			{{-- quizze --}}
			<div class="tab-pane fade" id="custom-content-below-quizze" role="tabpanel" aria-labelledby="custom-content-below-quizze-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
							<h5 class="m-0" style="display: inline;">قائمة  الاختبارات </h5>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1" style="float: left;">
									إضافة اختبار 
									<i class="fas fa-plus"></i>
							</button>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
								<th>#</th>
								<th>العنوان</th>
								<th> التاريخ</th>
								<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($courses->CourseQuizz as $key => $value)
								<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->title}}</td>
								<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
								<td>
								<a href="" 
									class="btn btn-info btn-sm edit1"
									data-toggle     ="modal"
									data-target     ="#modal-updateq"
									data-id         = "{{$value->id}}"
									data-title      = "{{$value->title}}"
									data-residuum   = "{{$value->residuum}}"
									data-accepted   = "{{$value->accepted}}"
									data-good       = "{{$value->good}}"
									data-very       = "{{$value->very_good}}"
									data-excellent  = "{{$value->excellent}}"
									data-folder     = "{{$value->folder_id}}"
									>  تعديل <i class="fas fa-edit"></i></a>
									<a href="{{route('Editquizze',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تفاصيل الاختبار <i class="fas fa-eye"></i></a>
									<form action="{{route('Deletequizze')}}" method="post" style="display: inline-block;">
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

						{{-- add quizze modal --}}
						<div class="modal fade" id="modal-primary1">
							<div class="modal-dialog">
								<div class="modal-content bg-primary">
								<div class="modal-header">
									<h4 class="modal-title">إضافة اختبار جديد</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<form action="{{route('storequizze')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
									<div class="row">
										<input type="hidden" name="id" value="{{$courses->id}}">
										<div class="col-sm-6">
											<label> عنوان الأختبار</label> <span class="text-danger">*</span>
											<input type="text" name="quizze_title" class="form-control" placeholder=" عنوان الأختبار " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label> نسبة الرسوب</label> <span class="text-danger">*</span>
											<input type="number" name="residuum" class="form-control" placeholder=" نسبة الرسوب " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label> نسبة المقبول</label> <span class="text-danger">*</span>
											<input type="number" name="accepted" class="form-control" placeholder=" نسبة المقبول " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label> نسبة الجيد</label> <span class="text-danger">*</span>
											<input type="number" name="good" class="form-control" placeholder=" نسبة الجيد " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label> نسبة الجيد جدا</label> <span class="text-danger">*</span>
											<input type="number" name="very_good" class="form-control" placeholder=" نسبة الجيد جدا " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label> نسبة الامتياذ</label> <span class="text-danger">*</span>
											<input type="number" name="excellent" class="form-control" placeholder=" نسبة الامتياذ " required="" style="margin-bottom: 10px"></br>
										</div>
										<div class="col-sm-6">
											<label>  المجلدات <span class="text-danger">*</span></label>
											<select name="folder_id" class="form-control">
												<option value="" disabled selected>إختيار مجلد </option>
												@foreach($courses->CourseOfflineFolders as $value)
													<option value="{{$value->id}}">{{$value->name}}</option>
												@endforeach
											</select>
										</div>
									</div>

										<button type="submit" id="submitq" style="display: none;"></button>
										
									</form>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-outline-light store">حفظ</button>
									<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
								</div>
							</div>
						</div>

						{{-- edit quizze modal --}}
						<div class="modal fade" id="modal-updateq">
							<div class="modal-dialog">
								<div class="modal-content bg-info">
								<div class="modal-header">
									<h4 class="modal-title">تعديل الأختبار : <span class="item_name"></span></h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
									<form action="{{route('updatequizze')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_quizze_id" value="">
										<div class="row">
										
											<div class="col-sm-6">
												<label> عنوان الأختبار</label> <span class="text-danger">*</span>
												<input type="text" name="edit_quizze_title" class="form-control" placeholder=" عنوان الأختبار " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label> نسبة الرسوب</label> <span class="text-danger">*</span>
												<input type="number" name="edit_residuum" class="form-control" placeholder=" نسبة الرسوب " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label> نسبة المقبول</label> <span class="text-danger">*</span>
												<input type="number" name="edit_accepted" class="form-control" placeholder=" نسبة المقبول " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label> نسبة الجيد</label> <span class="text-danger">*</span>
												<input type="number" name="edit_good" class="form-control" placeholder=" نسبة الجيد " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label> نسبة الجيد جدا</label> <span class="text-danger">*</span>
												<input type="number" name="edit_very" class="form-control" placeholder=" نسبة الجيد جدا " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label> نسبة الامتياذ</label> <span class="text-danger">*</span>
												<input type="number" name="edit_excellent" class="form-control" placeholder=" نسبة الامتياذ " required="" style="margin-bottom: 10px"></br>
											</div>
											<div class="col-sm-6">
												<label>  المجلدات <span class="text-danger">*</span></label>
												<select name="edit_folder_id" class="form-control">
													<option value="" disabled selected>إختيار مجلد </option>
													@foreach($courses->CourseOfflineFolders as $value)
														<option value="{{$value->id}}">{{$value->name}}</option>
													@endforeach
												</select>
											</div>
										</div>
										<button type="submit" id="updateq" style="display: none;"></button>
									</form>
								</div>
								<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-outline-light updateq">تحديث</button>
									<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
								</div>
							</div>
						</div>
					
					</div>
				</div>
			</div>

			{{-- comments --}}
			<div class="tab-pane fade" id="custom-content-below-comments" role="tabpanel" aria-labelledby="custom-content-below-comments-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;">قائمة  التعليقات </h5>
							</div>
							<div class="card-body">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>#</th>
											<th>الاسم</th>
											<th>التعليق</th>
											<th> التاريخ</th>
											<th>التحكم</th>
										</tr>
									</thead>
									<tbody>
									@foreach($courses->CourseComments as $key => $value)
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$value->Customer->name}}</td>
											<td>{{$value->comment}}</td>
											<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
											<td>
												<form action="{{route('Deletecomment')}}" method="post" style="display: inline-block;">
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
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
@endsection

@section('script')


<script type="text/javascript">

function ChooseAvatar1(){$("input[name='videos[]']").click()}

function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

$(".image").hover(function(){
  $(".dele",this).css("display", "block") }, 
  function(){
    $(".dele").css("display", "none");
});

$(".dele").hover(function(){
  $(this).css("display", "block") }, 
  function(){
});

$(".close").click(function(){
    var id = $(this).data("id");
    var $ele = $(this).parent();
    $.ajax(
    {
        url: "{{route('Deletefolder')}}",
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
            "id": id,
        },
        success: function (){
          $ele.fadeOut().remove();
        }
    });
   
});

$('.save').on('click',function(){
        $('#submit').click();
})

$('.store').on('click',function(){
        $('#submitq').click();
})

//edit folder
$('.edit').on('click',function(){
        var id      = $(this).data('id')
        var name   = $(this).data('name')
        
        $('.item_name').text(name)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_folder_name']").val(name)

    })

// update folder
$('.update').on('click',function(){
	$('#update').click();
}) 

// update quizz
$('.updateq').on('click',function(){
	$('#updateq').click();
})

	
//edit quizz
$('.edit1').on('click',function(){
	var id          = $(this).data('id')
	var title       = $(this).data('title')
	var residuum    = $(this).data('residuum')
	var accepted    = $(this).data('accepted')
	var good        = $(this).data('good')
	var very        = $(this).data('very')
	var excellent   = $(this).data('excellent')
	var folder      = $(this).data('folder')
	
	$("input[name='edit_quizze_id']").val(id)
	$("input[name='edit_quizze_title']").val(title)
	$("input[name='edit_residuum']").val(residuum)
	$("input[name='edit_accepted']").val(accepted)
	$("input[name='edit_good']").val(good)
	$("input[name='edit_very']").val(very)
	$("input[name='edit_excellent']").val(excellent)

	$("select[name='edit_folder_id'] > option").each(function() {
            if($(this).val() == folder)
            {
              $(this).attr("selected","")
			}
			
          });

})

//////map


$("#pac-input").focusin(function() {
    $(this).val();
  });
  
  $('#latitude').val();
  $('#longitude').val();
  
  
  // This example adds a search box to a map, using the Google Place Autocomplete
  // feature. People can enter geographical searches. The search box will return a
  // pick list containing a mix of places and predicted search terms.
  
  // This example requires the Places library. Include the libraries=places
  // parameter when you first load the API. For example:
  // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
  
  function initAutocomplete() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat:  $('#latitude').val(), lng:  $('#longitude').val() },
      zoom: 13,
      mapTypeId: 'roadmap'
    });
  
    // move pin and current location
    infoWindow = new google.maps.InfoWindow;
    geocoder = new google.maps.Geocoder();
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        map.setCenter(pos);
        var marker = new google.maps.Marker({
       
          map: map,
          title: 'موقعك الحالي'
        });
        markers.push(marker);
        marker.addListener('click', function() {
          geocodeLatLng(geocoder, map, infoWindow,marker);
        });
        // to get current position address on load
        google.maps.event.trigger(marker, 'click');
      }, function() {
        handleLocationError(true, infoWindow, map.getCenter());
      });
    } else {
      // Browser doesn't support Geolocation
      console.log('dsdsdsdsddsd');
      handleLocationError(false, infoWindow, map.getCenter());
    }
  
    var geocoder = new google.maps.Geocoder();
    google.maps.event.addListener(map, 'click', function(event) {
      SelectedLatLng = event.latLng;
      geocoder.geocode({
        'latLng': event.latLng
      }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            deleteMarkers();
            addMarkerRunTime(event.latLng);
            SelectedLocation = results[0].formatted_address;
            console.log( results[0].formatted_address);
            splitLatLng(String(event.latLng));
            $("#pac-input").val(results[0].formatted_address);
          }
        }
      });
    });
    function geocodeLatLng(geocoder, map, infowindow,markerCurrent) {
      var latlng = {lat: markerCurrent.position.lat(), lng: markerCurrent.position.lng()};
      /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
      $('#latitude').val(markerCurrent.position.lat());
      $('#longitude').val(markerCurrent.position.lng());
  
      geocoder.geocode({'location': latlng}, function(results, status) {
        if (status === 'OK') {
          if (results[0]) {
            map.setZoom(8);
            var marker = new google.maps.Marker({
              position: latlng,
              map: map
            });
            markers.push(marker);
            infowindow.setContent(results[0].formatted_address);
            SelectedLocation = results[0].formatted_address;
            $("#pac-input").val(results[0].formatted_address);
  
            infowindow.open(map, marker);
          } else {
            window.alert('No results found');
          }
        } else {
          window.alert('Geocoder failed due to: ' + status);
        }
      });
      SelectedLatLng =(markerCurrent.position.lat(),markerCurrent.position.lng());
    }
    function addMarkerRunTime(location) {
      var marker = new google.maps.Marker({
        position: location,
        map: map
      });
      markers.push(marker);
    }
    function setMapOnAll(map) {
      for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
      }
    }
    function clearMarkers() {
      setMapOnAll(null);
    }
    function deleteMarkers() {
      clearMarkers();
      markers = [];
    }
  
    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    $("#pac-input").val();
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
  
    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
      searchBox.setBounds(map.getBounds());
    });
  
    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
      var places = searchBox.getPlaces();
  
      if (places.length == 0) {
        return;
      }
  
      // Clear out the old markers.
      markers.forEach(function(marker) {
        marker.setMap(null);
      });
      markers = [];
  
      // For each place, get the icon, name and location.
      var bounds = new google.maps.LatLngBounds();
      places.forEach(function(place) {
        if (!place.geometry) {
          console.log("Returned place contains no geometry");
          return;
        }
        var icon = {
          url: place.icon,
          size: new google.maps.Size(100, 100),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25)
        };
  
        // Create a marker for each place.
        markers.push(new google.maps.Marker({
          map: map,
          icon: icon,
          title: place.name,
          position: place.geometry.location
        }));
  
  
        $('#latitude').val(place.geometry.location.lat());
        $('#longitude').val(place.geometry.location.lng());
  
        if (place.geometry.viewport) {
          // Only geocodes have viewport.
          bounds.union(place.geometry.viewport);
        } else {
          bounds.extend(place.geometry.location);
        }
      });
      map.fitBounds(bounds);
    });
  }
  
  function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
      'Error: The Geolocation service failed.' :
      'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
  }
  
  function splitLatLng(latLng){
    var newString = latLng.substring(0, latLng.length-1);
    var newString2 = newString.substring(1);
    var trainindIdArray = newString2.split(',');
    var lat = trainindIdArray[0];
    var Lng  = trainindIdArray[1];
  
    $("#latitude").val(lat);
    $("#longitude").val(Lng);
  }



  //////


</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EGasync defer"></script>


@endsection


