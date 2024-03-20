@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100%;
		height: 300px;
	}
	#avatar:hover{
		width: 100%;
		height: 300px;
		cursor: pointer;
	}
	.marbo{
		margin-bottom: 10px
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
.result_style{
		border: 1px solid #12a3b8;
		padding-top: 12px;
		border-radius: 22px;
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
					<a class="nav-link active" id="custom-content-below-show-tab" data-toggle="pill" href="#custom-content-below-show" role="tab" aria-controls="custom-content-below-show" aria-selected="true">بيانات المعرض</a>
				</li>


				{{-- cost --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-cost-tab" data-toggle="pill" href="#custom-content-below-cost" role="tab" aria-controls="custom-content-below-cost" aria-selected="false">    تكلفة الدخول</a>
				</li>

				{{-- images --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور المعرض</a>
				</li>

				{{-- showers --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-showers-tab" data-toggle="pill" href="#custom-content-below-showers" role="tab" aria-controls="custom-content-below-showers" aria-selected="false">العارضون </a>
				</li>

				{{-- speakers --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-speakers-tab" data-toggle="pill" href="#custom-content-below-speakers" role="tab" aria-controls="custom-content-below-speakers" aria-selected="false"> المتحدثون </a>
				</li>


			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">

			{{-- edit --}}
			<div class="tab-pane fade show active" id="custom-content-below-show" role="tabpanel" aria-labelledby="custom-content-below-show-tab">	
				<div class="row">
					<div class="col-sm-12">
							<div class="card-body">
								<form action="{{route('updateshow')}}" method="post" enctype="multipart/form-data">
									<div class="row">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$shows->id}}">
										<div class="col-sm-3" style="margin-top: 10px">
											<label class="text-primary">إسم المعرض <span class="text-danger">*</span></label>
											<input type="text" class="form-control" value="{{$shows->name}}" name="name" placeholder=" الاسم" required>
										</div>
									
										<div class="col-sm-3" style="margin-top: 10px">
											<label class="text-primary">  الدول <span class="text-danger">*</span></label>
											<select name="country_id" class="form-control country" required>
												<option value="" disabled selected>إختيار دولة</option>
												@foreach($countries as $value)
													<option value="{{$value->id}}" @if($shows->country_id == $value->id) selected @endif>{{$value->name}}</option>
												@endforeach
											</select>
										</div>
										<div class="col-sm-3" style="margin-top: 10px">
											<label class="text-primary"> المحافظة <span class="text-danger">*</span></label>
											<select name="city_id" class="form-control cities" required>

											</select>
										</div>
									
									
									
										<div class="col-sm-3" style="margin-top: 10px">
											<label class="text-primary">  النوع <span class="text-danger">*</span></label>
											<select name="paied" class="form-control" required>
												<option value="" disabled selected>إختيار </option>
												<option value="0"@if($shows->paied == '0') selected @endif>مجاني</option>
												<option value="1" @if($shows->paied == '1') selected @endif>مدفوع</option>
											</select>
										</div>

										{{-- majors --}}
										<div class="col-sm-12" style="margin-top: 10px">
											<label class="text-primary">الاقسام الرئيسية</label> <span class="text-danger">*</span><br>
											<div class="row">
												@foreach($sections as $value)
													<div class="col-sm-2 result_style">
														@if(in_array($value->id, $secs))
															<input type="checkbox"  class="major" checked name="Section[]" value="{{ $value->id }}">
															<label class="text-info">{{ $value->name }}</label>
														@else
														<input style="display: inline;width: 50%;" type="checkbox" class="major" name="Section[]" value="{{ $value->id }}">
                                                        <label class="text-info">{{ $value->name }}</label>
														@endif
													</div>
												@endforeach
											</div>
										</div>

										<div class="col-sm-4 marbo"  style="margin-top: 10px; position: relative;">
											<label  class="text-primary"> المواعيد <span class="text-danger">*</span></label>
											<div class="row times" >
												<div class="col-sm-1" style="padding: 0 ;position: absolute;    top: 0;left:88px">
													<button type="button" style="" class="btn btn-primary btn-block add_times">
														<i style="margin: 0px -7px " class="fas fa-plus"></i>
													</button>
												</div>
												@foreach($times as $m)
													<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $m}}" style="margin-top:10px">
														<div class="row">
															<div class="col-sm-8" style="padding-left: 5px ">
																<input type="date" name="times[]" value="{{$m}}" class="form-control"  placeholder="المواعيد" >
															</div>
															<div class="col-sm-1" style="padding: 0 ">
																<button type="button" class="btn btn-danger btn-block remove_times"data-code="{{strtotime(\Carbon\Carbon::now()) . $m}}">
																	<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
																</button>
															</div>
														</div>
													</div>
												@endforeach
											</div>
										</div>

										<div class="col-sm-4 marbo"  style="margin-top: 10px; position: relative;">
											<label  class="text-primary"> الوقت <span class="text-danger">*</span></label>
											<div class="row watchs" >
												<div class="col-sm-1" style="padding: 0 ;position: absolute;    top: 0;left:88px">
													<button type="button" style="" class="btn btn-primary btn-block add_watchs">
														<i style="margin: 0px -7px " class="fas fa-plus"></i>
													</button>
												</div>
												@if(!is_null($watchs))
													@foreach($watchs as $m)
														<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $m}}" style="margin-top:10px">
															<div class="row">
																<div class="col-sm-8" style="padding-left: 5px ">
																	<input type="text" name="watchs[]" value="{{$m}}" class="form-control"  placeholder="الوقت" >
																</div>
																<div class="col-sm-1" style="padding: 0 ">
																	<button type="button" class="btn btn-danger btn-block remove_watchs"data-code="{{strtotime(\Carbon\Carbon::now()) . $m}}">
																		<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
																	</button>
																</div>
															</div>
														</div>
													@endforeach
												@endif
											</div>
										</div>
										{{-- sections --}}
										<div class="col-sm-12">
											<label class="text-primary"> الجهات المنظمة</label> <span class="text-danger">*</span>
											<div class="row">
												@foreach($organisers as $value)
												@if(in_array($value->id, $majs))
													<div class="col-sm-2 result_style ">
														<input style="display: inline;width: 50%;" type="checkbox"  name="organs[]" value="{{ $value->id }}" checked>
														<label class="text-info">{{ $value->name }}</label>
																
													</div>
												@else
												<div class="col-sm-2 result_style ">
													<input style="display: inline;width: 50%;" type="checkbox" name="organs[]" value="{{ $value->id }}">
													<label class="text-info">{{ $value->name }}</label>
															
												</div>
												@endif
												@endforeach
											</div>
										</div>
									
										<div class="col-sm-6 m_top" style="margin-top: 10px">
											<label class="text-primary">نبزة مختصرة <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="8" name="desc" placeholder="نبزة مختصرة " required>{{$shows->desc}}</textarea>
										</div>  
										<div class="col-sm-6 marbo" style="margin-top: 10px">
											<label class="text-primary" >إختيار صورة <span class="text-danger"> * </span></label><br>
											<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
											<img src="{{asset('uploads/show/images/'.$shows->image)}}" style="width: 100%;height:200px" onclick="ChooseAvatar()" id="avatar">
										</div>
							
								<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
								</div>
							</form>
							</div>
						
					</div>
				</div>
			</div>

			{{-- cost --}}
			<div class="tab-pane fade" style="padding-top: 20px" id="custom-content-below-cost" role="tabpanel" aria-labelledby="custom-content-below-cost-tab">
				<div class="container-fluid" style="padding: 30px" >
					<form action="{{route('Updatetac')}}" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2" style="padding: 0 2px 0px 0px; margin-bottom:20px;">
								<button type="button" class="btn btn-primary btn-block add_local">
									<i class="fas fa-plus"></i>
								</button>
							</div>
							
							
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$shows->id}}">
									@if(count($shows->ShowTacs) == 0)
									<div class="col-sm-12" style="margin-top:20px">
									
										<div class="row loc"  style="margin-top: 20px">

						
										<div class="col-sm-5" style="padding: 0 15px 0 3px">
											<input type="text" name="kind[]" class="form-control" placeholder=" النوع" >
										</div>
										
										<div class="col-sm-5" style="padding: 0 15px 0 3px">
											<input type="text" name="price[]" class="form-control" placeholder=" السعر" >
										</div>
                                    
                                
								

											<div class="col-sm-1" style="padding: 0 0 0 1px">
												<button type="button" class="btn btn-danger btn-block remove_quantiti">
													<i class="fas fa-minus-circle"></i>
												</button>
											</div>
											
											
										</div>
									</div>
									@endif
									@foreach($shows->ShowTacs as $key => $value)
									<div class="col-sm-12" style="margin-top:20px">
										<div class="row loc"  style="margin-top: 20px">


										<div class="col-sm-5" style="padding: 0 15px 0 3px">
											<input type="text" name="kind[]"  value="{{ $value->name}}" class="form-control" placeholder=" النوع" >
										</div>
										
										<div class="col-sm-5" style="padding: 0 15px 0 3px">
											<input type="text" name="price[]"  value="{{ $value->price}}" class="form-control" placeholder=" السعر" >
										</div>
                                    
                                
								

											<div class="col-sm-1" style="padding: 0 0 0 1px">
												<button type="button" class="btn btn-danger btn-block remove_quantiti">
													<i class="fas fa-minus-circle"></i>
												</button>
											</div>
											
											
										</div>
									</div>
									@endforeach
								</div>
								<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
						</div>
					</form>
				</div>
			
			

			{{-- images --}}
			<div class="tab-pane fade"  id="custom-content-below-images" role="tabpanel" aria-labelledby="custom-content-below-images-tab">
			<div class="container-fluid">
					<div class="row">
						<div class="col-sm-12">
							<form action="{{route('storeImagesshow')}}" method="post" enctype="multipart/form-data">
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$shows->id}}">
									<div class="card  card-outline">
									<div class="card-header">
										<h5 class="m-0" style="display: inline;" style="float: right">اضافة صور</h5>
										<div class="btn btn-primary" style="float: left;height:36px;">
										<input type="file" name="images[]" id="gallery"  style="display: none;" accept="image/*" required multiple>
										<label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-sm-12 marbo img">
												<div class="gallery">
													@foreach($shows->ShowImgs as $key => $value)
													<div class="filtr-item image col-sm-2"  style="position: relative;display: inline-block;" data-category="1" data-sort="white sample">
														<input type="hidden" name="idd" value="{{$value->id}}">
														<button type="button" data-id="{{$value->id}}" class="btn btn-danger btn-sm dele close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 3px 8px;color: #fff;border-radius: 50%;top: 30px;right: 20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		
														<img src="{{asset('uploads/show/alboum/'.$value->image)}}" class="img-fluid mb-2  bounceIn" alt="black sample"/>
													</div>
													@endforeach
												</div>
											</div>
										</div>
									</div>
								</div>
								<button style="width: 50%; margin-left: auto; margin-right: auto;margin-bottom:30px " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
							</form>	
						</div>

							
					</div>
				</div>
			</div>

			{{-- showers --}}
			<div class="tab-pane fade" id="custom-content-below-showers" role="tabpanel" aria-labelledby="custom-content-below-showers-tab">
				<div class="row">
					<div class="col-sm-12">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;" style="float: right">العارضون</h5>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
									إضافة عارض 
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>الصورة</th>
								<th>إسم العارض</th>
								<th>التاريخ</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($shows->Showers as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/show/shower/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
									<td>{{$value->name}}</td>
									<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
									<td>
										<a href="" 
										class="btn btn-info btn-sm edit"
										data-toggle="modal"
										data-target="#modal_edit"
										data-id    = "{{$value->id}}"
										data-name  = "{{$value->name}}"
										data-image = "{{$value->image}}"
										>  تعديل <i class="fas fa-edit"></i></a>
										<form action="{{route('Deleteshower')}}" method="post" style="display: inline-block;">
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$value->id}}">
											<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
										</form>
									</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							</div>
						
					</div>

					{{-- edit shower modal --}}
					<div class="modal fade" id="modal_edit">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title"> تعديل </h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('updateshower')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_shower_id" value="">
										<label>إسم العارض</label> <span class="text-danger">*</span>
										<input type="text" name="edit_shower_name" class="form-control" placeholder="إسم العارض " required="" style="margin-bottom: 10px">
										<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
										<input type="file" name="edit_shower_image" accept="image/*" onchange="loadAvatar3(event)" style="display: none;">
										<img src="" class="test" style="max-width: 100%;
										height:250px;cursor: pointer;" onclick="ChooseAvatar3()" id="avatar3">
										<button type="submit" class="update" id="submit" style="display: none;"></button>
								</form>
								</div>
								<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-outline-light upd save">حفظ</button>
								<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
							</div>
						</div>
					</div>

					{{-- add shower modal --}}
					<div class="modal fade" id="modal-primary">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة عارض جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('storeshower')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="shower_id" value="{{$shows->id}}">
									<label>إسم العارض</label> <span class="text-danger">*</span>
									<input type="text" name="shower_name" class="form-control" placeholder="إسم العارض " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="shower_image" accept="image/*" onchange="loadAvatar6(event)" style="display: none;">
									<img src="{{asset('dist/img/placeholder.png')}}" style="width: 100%;height:250px;cursor: pointer;" onclick="ChooseAvatar6()" id="avatar6">
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
			
			{{-- speakers --}}
			<div class="tab-pane fade" id="custom-content-below-speakers" role="tabpanel" aria-labelledby="custom-content-below-speakers-tab">
			<div class="row">
					<div class="col-sm-12">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;" style="float: right">المتحدثون</h5>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1" style="float: left;">
									إضافة متحدث 
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>الصورة</th>
								<th>إسم المتحدث</th>
								<th>التاريخ</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($shows->Speakers as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/show/speaker/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
									<td>{{$value->name}}</td>
									<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
									<td>
										<a href="" 
										class="btn btn-info btn-sm edit1"
										data-toggle="modal"
										data-target="#modal_edit1"
										data-id    = "{{$value->id}}"
										data-name  = "{{$value->name}}"
										data-image = "{{$value->image}}"
										data-type = "{{$value->type}}"
										>  تعديل <i class="fas fa-edit"></i></a>
										<form action="{{route('Deletespeaker')}}" method="post" style="display: inline-block;">
											{{csrf_field()}}
											<input type="hidden" name="id" value="{{$value->id}}">
											<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
										</form>
									</td>
									</tr>
								@endforeach
								</tbody>
							</table>
							</div>
						
					</div>

					{{-- edit speaker modal --}}
					<div class="modal fade" id="modal_edit1">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title"> تعديل </h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('updatespeaker')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_speaker_id" value="">
										<label>إسم المتحدث</label> <span class="text-danger">*</span>
										<input type="text" name="edit_speaker_name" class="form-control" placeholder="إسم المتحدث " required="" style="margin-bottom: 10px">
										<label>منصب المتحدث</label> <span class="text-danger">*</span>
										<input type="text" name="edit_speaker_type" class="form-control" placeholder="منصب المتحدث " required="" style="margin-bottom: 10px">
										<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
										<input type="file" name="edit_speaker_image" accept="image/*" onchange="loadAvatar0(event)" style="display: none;">
										<img src="" class="test1" style="max-width: 100%;
										height:250px;cursor: pointer;" onclick="ChooseAvatar0()" id="avatar0">
										<button type="submit" class="update" id="submit" style="display: none;"></button>
								</form>
								</div>
								<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-outline-light upd1 save">حفظ</button>
								<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
							</div>
						</div>
					</div>

					{{-- add speaker modal --}}
					<div class="modal fade" id="modal-primary1">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة متحدث جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('storespeaker')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="speaker_id" value="{{$shows->id}}">
									<label>إسم المتحدث</label> <span class="text-danger">*</span>
									<input type="text" name="speaker_name" class="form-control" placeholder="إسم المتحدث " required="" style="margin-bottom: 10px">
									<label>منصب المتحدث</label> <span class="text-danger">*</span>
									<input type="text" name="speaker_type" class="form-control" placeholder="منصب المتحدث " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="speaker_image" accept="image/*" onchange="loadAvatar2(event)" style="display: none;">
									<img src="{{asset('dist/img/placeholder.png')}}" style="width: 100%;height:250px;cursor: pointer;" onclick="ChooseAvatar2()" id="avatar2">
									<button type="submit" class="submit" id="submit" style="display: none;"></button>
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


</script>
<script type="text/javascript">
//edit image
function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar2(){$("input[name='speaker_image']").click()}
var loadAvatar2 = function(event) {
var output = document.getElementById('avatar2');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar6(){$("input[name='shower_image']").click()}
var loadAvatar6 = function(event) {
var output = document.getElementById('avatar6');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar3(){$("input[name='edit_shower_image']").click()}
var loadAvatar3 = function(event) {
var output = document.getElementById('avatar3');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar0(){$("input[name='edit_speaker_image']").click()}
var loadAvatar0 = function(event) {
var output = document.getElementById('avatar0');
output.src = URL.createObjectURL(event.target.files[0]);
};


function ChooseAvatar1(){$("input[name='images[]']").click()}	


$(".image").hover(function(){
  $(".dele",this).css("display", "block") }, 
  function(){
    $(".dele").css("display", "none");
});
$(".dele").hover(function(){
  $(this).css("display", "block") }, 
  function(){

});

$(".image").hover(function(){
  $(".note",this).css("display", "block") }, 
  function(){
    $(".note").css("display", "none");
});
$(".note").hover(function(){
  $(this).css({"display": "block", "background": "rgba(76, 175, 80, 1)"}) }, 
  function(){

});

$(function() {
// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {

	if (input.files) {
		var filesAmount = input.files.length;

		for (i = 0; i < filesAmount; i++) {
			var reader = new FileReader();

			reader.onload = function(event) {
				$($.parseHTML('<img class="img-fluid mb-2  bounceIn">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
			}

			reader.readAsDataURL(input.files[i]);
		}
	}

};

$('#gallery').on('change', function() {
	imagesPreview(this, 'div.gallery');
});
});
	
$(".close").click(function(){
    var id = $(this).data("id");
    var $ele = $(this).parent();
    $.ajax(
    {
        url: "{{route('deleteimagesshow')}}",
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


//edit shower
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var image      = $(this).data('image')
	
	$('.item_name').text(name)
	$("input[name='edit_shower_id']").val(id)
	$("input[name='edit_shower_name']").val(name)

	var url =  '{{ url("uploads/show/shower/") }}/' + image
	$('.test').attr('src',url);

})



//edit speaker
$('.edit1').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var image      = $(this).data('image')
	var type      = $(this).data('type')
	
	$('.item_name').text(name)
	$("input[name='edit_speaker_id']").val(id)
	$("input[name='edit_speaker_name']").val(name)
	$("input[name='edit_speaker_type']").val(type)

	var url =  '{{ url("uploads/show/speaker/") }}/' + image
	$('.test1').attr('src',url);

})



$('.upd').on('click',function(){
	$('.update').click();
})

$('.save').on('click',function(){
	$('.submit').click();
})



$('.upd1').on('click',function(){
	$('.update').click();
})

$('.save1').on('click',function(){
	$('.submit').click();
})

$('.upd0').on('click',function(){
	$('.update0').click();
})

$('.save0').on('click',function(){
	$('.submit0').click();
})

// get cities
function GetDefaultsubSections()
{
	var data = {
		country    : {{$shows->country_id}},
		_token        : $("input[name='_token']").val()
	}
	

		$.ajax({
		url     : "{{ url('get-cities') }}",
		method  : 'post',
		data    : data,
		success : function(s,result){
			$('.cities').html('')
			$('.cities').append(`
			`);
			$.each(s,function(k,v){
			if(v.id == "{{$shows->city_id}}")
			{
				$('.cities').append(`
					<option value="${v.id}" selected>${v.name}</option>
				`);
			}else{
				$('.cities').append(`
					<option value="${v.id}">${v.name}</option>
				`);
			}
		})
	}});
}
GetDefaultsubSections()

// get sub sections
$(document).on('change','.country', function(){

var data = {
country    : $(this).val(),
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-cities') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        $('.cities').html('')
        $('.cities').append(`
        `);
        $.each(s,function(k,v){
        $('.cities').append(`
            <option value="${v.id}">${v.name}</option>
        `);
    })
    }});

});

//contacts

$(document).on('click','.add_times',function(){
	$('.times').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="date" name="times[]" class="form-control" placeholder="المواعيد" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_times" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_times',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_watchs',function(){
	$('.watchs').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="watchs[]" class="form-control" placeholder="الوقت" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_watchs" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_watchs',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})




$(document).on('click','.add_local',function(){
		$('.loc').append(
			`
			<div class="col-sm-12 father${Date.now()}" style="margin-top:20px">
				<div class="row">
					<div class="col-sm-5" style="padding: 0 15px 0 3px">
						<input type="text" name="kind[]" class="form-control" placeholder=" النوع" >
					</div>
					
					<div class="col-sm-5" style="padding: 0 15px 0 3px">
						<input type="text" name="price[]" class="form-control" placeholder=" السعر" >
					</div>
                                    

					<div class="col-sm-1" style="padding: 0 0 0 1px">
						<button type="button" class="btn btn-danger btn-block remove_quantiti" data-code="${Date.now()}">
							<i class="fas fa-minus-circle"></i>
						</button>
					</div>
				</div>
			</div>
			`
		);
	})

	$(document).on('click','.remove_quantiti',function(){
		var cla = '.father'+$(this).parent().parent().remove();
		$(cla).remove();
	})



</script>



 
@endsection


