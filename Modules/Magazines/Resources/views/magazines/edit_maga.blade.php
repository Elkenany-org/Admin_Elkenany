@extends('layouts.app')

@section('style')
<style type="text/css">

	.marbo{
		margin-bottom: 10px
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
					<a class="nav-link active" id="custom-content-below-maga-tab" data-toggle="pill" href="#custom-content-below-maga" role="tab" aria-controls="custom-content-below-maga" aria-selected="true">بيانات المجلة</a>
				</li>

				{{-- social --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-social-tab" data-toggle="pill" href="#custom-content-below-social" role="tab" aria-controls="custom-content-below-social" aria-selected="false">التواصل الاجتماعي</a>
				</li>

				{{-- cont --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-cont-tab" data-toggle="pill" href="#custom-content-below-cont" role="tab" aria-controls="custom-content-below-cont" aria-selected="false">التواصل </a>
				</li>

				{{-- address --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-address-tab" data-toggle="pill" href="#custom-content-below-address" role="tab" aria-controls="custom-content-below-address" aria-selected="false">   العناوين الاضافية</a>
				</li>

				{{-- images --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور المجلة</a>
				</li>

				{{-- guides --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-guides-tab" data-toggle="pill" href="#custom-content-below-guides" role="tab" aria-controls="custom-content-below-guides" aria-selected="false">دلائل </a>
				</li>

				</li>

			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">

			{{-- edit --}}
			<div class="tab-pane fade show active" id="custom-content-below-maga" role="tabpanel" aria-labelledby="custom-content-below-maga-tab">	
				<div class="row">
					<div class="col-sm-12">
							<div class="card-body">
								<form action="{{route('updatemagazine')}}" method="post" enctype="multipart/form-data">
									<div class="row">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$magazine->id}}">
										<input type="hidden"  value="{{$magazine->latitude}}" id="latitude" name="latitude">
										<input type="hidden" value="{{$magazine->longitude}}" id="longitude"  name="longitude">
										<div class="col-sm-12 marbo">
											<div class="row">
												<div class="col-sm-2">
													<label>الاسم <span class="text-danger">*</span></label>
													<input type="text" value="{{$magazine->name}}" class="form-control" name="name" placeholder=" الاسم" required></input>
												</div>
												
												<div class="col-sm-2">
													<label>التلفون <span class="text-danger">*</span></label>
													<input type="text" value="{{$magazine->manage_phone}}" class="form-control" name="manage_phone" placeholder=" التلفون" required></input>
												</div>
												<div class="col-sm-2">
													<label>الايميل <span class="text-danger">*</span></label>
													<input type="text" value="{{$magazine->manage_email}}" class="form-control" name="manage_email" placeholder=" الايميل" required></input>
												</div>
												<div class="col-sm-2">
													<label>  المحافظة <span class="text-danger">*</span></label>
													<select name="city_id" class="form-control city_id" required>
														<option value="" disabled selected>إختيار محافظة</option>
														@foreach($cities as $value)
															<option value="{{$value->id}}"@if($magazine->city_id == $value->id) selected @endif>{{$value->name}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-2">
													<label>  النوع <span class="text-danger">*</span></label>
													<select name="paied" class="form-control" required>
														<option value="" disabled selected>إختيار </option>
														<option value="0"@if($magazine->paied == '0') selected @endif>مجاني</option>
														<option value="1"@if($magazine->paied == '1') selected @endif>مدفوع</option>
													</select>
												</div>
											</div>
										</div>

										{{-- majors --}}
										<div class="col-sm-12" style="margin-top: 10px">
											<label class="text-primary">الاقسام الرئيسية</label> <span class="text-danger">*</span><br>
											<div class="row">
												@foreach($sections as $value)
													<div class="col-sm-2 result_style">
														@if(in_array($value->id, $majs))
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
									
										<div class="col-sm-6 marbo">
										<label>نبزة مختصرة <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="7" name="short_desc" placeholder="نبزة مختصرة " required>{{$magazine->short_desc}}</textarea>
										</div> 
										<div class="col-sm-6 marbo">
										<label>عن المجلة <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="7" name="about" placeholder="عن المجلة" required>{{$magazine->about}}</textarea>
										</div>  
										<div class="col-sm-6 marbo">
											<label >إختيار صورة <span class="text-primary"> * </span></label><br>
											<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
											<img src="{{asset('uploads/magazine/images/'.$magazine->image)}}" onclick="ChooseAvatar()" id="avatar">
										</div>
										<div class="col-sm-6 marbo">
										<label for="exampleInputEmail1">العنوان</label>
										<input type="text" id="pac-input" class="form-control" placeholder="  " value="{{ $magazine->address }}" name="address">
										<div class="validate-input" id="map" style="min-height: 300px;min-width: 250px;"></div>
										</div>
										
									</div>
									<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
								</form>
							</div>
						
					</div>
				</div>
			</div>

			{{-- cont --}}
			<div class="tab-pane fade" style="padding-bottom: 30px;" id="custom-content-below-cont" role="tabpanel" aria-labelledby="custom-content-below-cont-tab">
				<div class="container-fluid">
				<form action="{{route('Updatecontactm')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$magazine->id}}">
					<div class="row">
					<div class="col-sm-4 marbo"  style="margin-top: 20px;position: relative;">
						<label> الموبايل <span class="text-danger">*</span></label>
						<div class="row mobiles"  style="position: relative;" >
							<div class="col-sm-8" style="padding-left: 5px ">

							</div>
							<div class="col-sm-1" style="padding: 0 ;">
								<button type="button" style="position: absolute;    top: -35px;" class="btn btn-primary btn-block add_mobiles">
									<i style="margin: 0px -7px " class="fas fa-plus"></i>
								</button>
							</div>
							@foreach($mobiles as $m)
								<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $m}}" style="margin-top:10px">
									<div class="row">
										<div class="col-sm-8" style="padding-left: 5px ">
											<input type="text" name="mobiles[]" value="{{$m}}" class="form-control"  placeholder="الموبايل" >
										</div>
										<div class="col-sm-1" style="padding: 0 ">
											<button type="button" class="btn btn-danger btn-block remove_mobiles"data-code="{{strtotime(\Carbon\Carbon::now()) . $m}}">
												<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
											</button>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="col-sm-4 marbo"  style="margin-top: 20px">
						<label> الهاتف الارضي <span class="text-danger">*</span></label>
						<div class="row phones"  style="position: relative;" >
							<div class="col-sm-8" style="padding-left: 5px ">
							</div>
							<div class="col-sm-1" style="padding: 0 ">
								<button type="button" style="position: absolute;    top: -35px;" class="btn btn-primary btn-block add_phones">
									<i style="margin: 0px -7px " class="fas fa-plus"></i>
								</button>
							</div>
							@foreach($phones as $q)
								<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $q}}" style="margin-top:10px">
									<div class="row">
										<div class="col-sm-8" style="padding-left: 5px ">
											<input type="text" name="phones[]" value="{{$q}}" class="form-control"  placeholder="الهاتف الارضي" >
										</div>
										<div class="col-sm-1" style="padding: 0 ">
											<button type="button" class="btn btn-danger btn-block remove_phones"data-code="{{strtotime(\Carbon\Carbon::now()) . $q}}">
												<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
											</button>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
					<div class="col-sm-4 marbo"  style="margin-top: 20px">
						<label> البريد <span class="text-danger">*</span></label>
						<div class="row emails"  style="position: relative;" >
							<div class="col-sm-8" style="padding-left: 5px ">
				
							</div>
							<div class="col-sm-1" style="padding: 0 ">
								<button type="button" style="position: absolute;    top: -35px;" class="btn btn-primary btn-block add_emails">
									<i style="margin: 0px -7px " class="fas fa-plus"></i>
								</button>
							</div>
							@foreach($emails as $m)
							<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $m}}" style="margin-top:10px">
									<div class="row">
										<div class="col-sm-8" style="padding-left: 5px ">
											<input type="text" name="emails[]" value="{{$m}}" class="form-control"  placeholder="البريد" >
										</div>
										<div class="col-sm-1" style="padding: 0 ">
											<button type="button" class="btn btn-danger btn-block remove_emails"data-code="{{strtotime(\Carbon\Carbon::now()) . $m}}">
												<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
											</button>
										</div>
									</div>
							</div>
							@endforeach
						</div>
					</div>
					<div class="col-sm-4 marbo"  style="margin-top: 20px;position: relative;">
						<label> الفاكس <span class="text-danger">*</span></label>
						<div class="row faxs"  style="position: relative;" >
							<div class="col-sm-8" style="padding-left: 5px ">

							</div>
							<div class="col-sm-1" style="padding: 0 ;">
								<button type="button" style="position: absolute;    top: -35px;" class="btn btn-primary btn-block add_faxs">
									<i style="margin: 0px -7px " class="fas fa-plus"></i>
								</button>
							</div>
							@foreach($faxs as $f)
								<div class="col-sm-12 father{{strtotime(\Carbon\Carbon::now()) . $f}}" style="margin-top:10px">
									<div class="row">
										<div class="col-sm-8" style="padding-left: 5px ">
											<input type="text" name="faxs[]" value="{{$f}}" class="form-control"  placeholder="الفاكس" >
										</div>
										<div class="col-sm-1" style="padding: 0 ">
											<button type="button" class="btn btn-danger btn-block remove_faxs"data-code="{{strtotime(\Carbon\Carbon::now()) . $f}}">
												<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
											</button>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>

					</div>
					<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
				</form>
				</div>
			</div>

			{{-- social --}}
			<div class="tab-pane fade" id="custom-content-below-social" role="tabpanel" aria-labelledby="custom-content-below-social-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-body">
					
							<form action="{{route('storesocialm')}}" method="post" enctype="multipart/form-data">
							{{csrf_field()}}
							<input type="hidden" name="id" value="{{$magazine->id}}">
							<div class="row">
							    @foreach($magazine->MagazinSocialmedia as $media)
								<input type="hidden" name="social_id[]" class="form-control" value="{{$media->Social->id}}" required>
									<div class="col-sm-12" style="margin-top: 20px;margin-bottom: 20px ">
										<div class="row">
												<div class="col-sm-1">
													<img src="{{$media->Social->social_icon}}" style="width:75%;height:50px">
												</div>
												<div class="col-sm-2" style="line-height: 50px">
													{{$media->Social->social_name}}
												</div>
											<div class="col-sm-6" >
												<input type="text" name="social_link[]" value="{{$media->social_link}}" class="form-control" placeholder="الرابط">
											</div>
										</div>
									</div>
								@endforeach
								@foreach($social as $media)
									<input type="hidden" name="social_id[]" class="form-control" value="{{$media->id}}">
										<div class="col-sm-12" style="margin-bottom: 20px">
											<div class="row">
												<div class="col-sm-1">
													<img src="{{$media->social_icon}}" style="width:75%;height:50px">
												</div>
												<div class="col-sm-2" style="line-height: 50px">
													{{$media->social_name}}
												</div>
												<div class="col-sm-6" >
													<input type="text" name="social_link[]" value="" class="form-control" placeholder="الرابط">
												</div>
											</div>
										</div>
								@endforeach
							</div>
							<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
							</form>
						</div>
					</div>
				</div>
			</div>

			{{-- address --}}
			<div class="tab-pane fade" style="padding-top: 20px" id="custom-content-below-address" role="tabpanel" aria-labelledby="custom-content-below-address-tab">
				<div class="container-fluid" style="padding: 30px" >
					<form action="{{route('Updatelocalm')}}" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2" style="padding: 0 2px 0px 0px; margin-bottom:20px;">
								<button type="button" class="btn btn-primary btn-block add_local">
									<i class="fas fa-plus"></i>
								</button>
							</div>
							
							
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$magazine->id}}">
									@if(count($magazine->Magazineaddress) == 0)
									<div class="col-sm-12" style="margin-top:20px">
									
										<div class="row loc"  style="margin-top: 20px">

						
											<div class="col-sm-4" style="padding: 0 15px 0 3px">
												<input type="text" name="loca[]"  class="form-control" placeholder=" العنوان" >
											</div>
											
											<div class="col-sm-3" style="padding: 0 15px 0 3px">
												<input type="text" name="lat[]"  class="form-control" placeholder=" lat" >
											</div>
											
											<div class="col-sm-3" style="padding: 0 15px 0 3px">
												<input type="text" name="long[]" class="form-control" placeholder=" long" >
											</div>

								

											<div class="col-sm-1" style="padding: 0 0 0 1px">
												<button type="button" class="btn btn-danger btn-block remove_quantiti">
													<i class="fas fa-minus-circle"></i>
												</button>
											</div>
											
											
										</div>
									</div>
									@endif
									@foreach($magazine->Magazineaddress as $key => $value)
									<div class="col-sm-12" style="margin-top:20px">
										<div class="row loc"  style="margin-top: 20px">

						
											<div class="col-sm-4" style="padding: 0 15px 0 3px">
												<input type="text" name="loca[]" value="{{ $value->address}}" class="form-control" placeholder=" العنوان" >
											</div>
											
											<div class="col-sm-3" style="padding: 0 15px 0 3px">
												<input type="text" name="lat[]" value="{{ $value->latitude}}" class="form-control" placeholder=" lat" >
											</div>
											
											<div class="col-sm-3" style="padding: 0 15px 0 3px">
												<input type="text" name="long[]"  value="{{ $value->longitude}}" class="form-control" placeholder=" long" >
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
				<div class="row">
						<div class="col-sm-12">
								<div class="card-header">
								<h5 class="m-0" style="display: inline;" style="float: right">البومات الصور</h5>
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary0" style="float: left;">
										إضافة البوم 
										<i class="fas fa-plus"></i>
									</button>
								</div>
								<div class="card-body">
								<table id="example1" class="table table-bordered table-hover table-striped">
									<thead>
									<tr>
									<th>#</th>
									<th>الصورة</th>
									<th>إسم الالبوم</th>
									<th>التاريخ</th>
									<th>التحكم</th>
									</tr>
									</thead>
									<tbody>
									@foreach($magazine->Magazingallary as $key => $value)
										<tr>
										<td>{{$key+1}}</td>
										<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/gallary/avatar/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
										<td>{{$value->name}}</td>
										<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
										<td>
											<a href="" 
											class="btn btn-info btn-sm edit0"
											data-toggle="modal"
											data-target="#modal_edit0"
											data-id    = "{{$value->id}}"
											data-name  = "{{$value->name}}"
											data-image = "{{$value->image}}"
											>  تعديل <i class="fas fa-edit"></i></a>
											<a href="{{route('gallarym',$value->id)}}" class="btn btn-primary btn-sm">  صور الالبوم <i class="fas fa-eye"></i></a>
											<form action="{{route('Deletegallarym')}}" method="post" style="display: inline-block;">
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

						{{-- edit gallary modal --}}
						<div class="modal fade" id="modal_edit0">
							<div class="modal-dialog">
								<div class="modal-content bg-primary">
									<div class="modal-header">
									<h4 class="modal-title"> تعديل </h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
									<form action="{{route('updategallarym')}}" method="post" enctype="multipart/form-data">
											{{csrf_field()}}
											<input type="hidden" name="edit_gallary_id" value="">
											<label>إسم الالبوم</label> <span class="text-danger">*</span>
											<input type="text" name="edit_gallary_name" class="form-control" placeholder="إسم الالبوم " required="" style="margin-bottom: 10px">
											<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
											<input type="file" name="edit_gallary_image" accept="image/*" onchange="loadAvatar0(event)" style="display: none;">
											<img src="" class="test0" style="width: 100%;
											height:250px;cursor: pointer;" onclick="ChooseAvatar0()" id="avatar0">
											<button type="submit" class="update0" id="submit" style="display: none;"></button>
									</form>
									</div>
									<div class="modal-footer justify-content-between">
									<button type="button" class="btn btn-outline-light upd0 save0">حفظ</button>
									<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
									</div>
								</div>
							</div>
						</div>

						{{-- add gallary modal --}}
						<div class="modal fade" id="modal-primary0">
							<div class="modal-dialog">
								<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title">إضافة البوم جديد</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('storegallarym')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="maga_id" value="{{$magazine->id}}">
										<label>إسم الالبوم</label> <span class="text-danger">*</span>
										<input type="text" name="gallary_name" class="form-control" placeholder="إسم الالبوم " required="" style="margin-bottom: 10px">
										<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
										<input type="file" name="gallary_image" accept="image/*" onchange="loadAvatar5(event)" style="display: none;">
										<img src="{{asset('dist/img/placeholder.png')}}" style="width: 100%;height:250px;cursor: pointer;" onclick="ChooseAvatar5()" id="avatar5">
										<button type="submit" class="submit0" id="submit" style="display: none;"></button>
								</form>
								</div>
								<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-outline-light save0">حفظ</button>
								<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
							</div>
						</div>

								
					</div>
				</div>
			</div>

			{{-- guides --}}
			<div class="tab-pane fade" id="custom-content-below-guides" role="tabpanel" aria-labelledby="custom-content-below-guides-tab">
				<div class="row">
					<div class="col-sm-12">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;" style="float: right">الدلائل</h5>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
									إضافة دليلة 
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>الصورة</th>
								<th>إسم الدليلة</th>
								<th>التاريخ</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($magazine->Magazinguide as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/magazine/guides/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
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
										data-link = "{{$value->link}}"
										>  تعديل <i class="fas fa-edit"></i></a>
										<form action="{{route('Deleteguide')}}" method="post" style="display: inline-block;">
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

					{{-- edit guide modal --}}
					<div class="modal fade" id="modal_edit">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title"> تعديل </h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('updateguide')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_guide_id" value="">
										<label>إسم الدليلة</label> <span class="text-danger">*</span>
										<input type="text" name="edit_guide_name" class="form-control" placeholder="إسم الدليلة " required="" style="margin-bottom: 10px">
										<label>لينك الدليلة</label> <span class="text-danger">*</span>
										<input type="text" name="edit_guide_link" class="form-control" placeholder="لينك الدليلة " required="" style="margin-bottom: 10px">
										<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
										<input type="file" name="edit_guide_image" accept="image/*" onchange="loadAvatar3(event)" style="display: none;">
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

					{{-- add guide modal --}}
					<div class="modal fade" id="modal-primary">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة دليلة جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('storeguide')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="guide_id" value="{{$magazine->id}}">
									<label>إسم الدليلة</label> <span class="text-danger">*</span>
									<input type="text" name="guide_name" class="form-control" placeholder="إسم الدليلة " required="" style="margin-bottom: 10px">
									<label>لينك الدليلة</label> <span class="text-danger">*</span>
										<input type="text" name="guide_link" class="form-control" placeholder="لينك الدليلة " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="guide_image" accept="image/*" onchange="loadAvatar2(event)" style="display: none;">
									<img src="{{asset('dist/img/placeholder.png')}}" style="max-width: 100%;height:250px;cursor: pointer;" onclick="ChooseAvatar2()" id="avatar2">
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

function ChooseAvatar2(){$("input[name='guide_image']").click()}
var loadAvatar2 = function(event) {
var output = document.getElementById('avatar2');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar3(){$("input[name='edit_guide_image']").click()}
var loadAvatar3 = function(event) {
var output = document.getElementById('avatar3');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar0(){$("input[name='edit_gallary_image']").click()}
var loadAvatar0 = function(event) {
var output = document.getElementById('avatar0');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar5(){$("input[name='gallary_image']").click()}
var loadAvatar5 = function(event) {
var output = document.getElementById('avatar5');
output.src = URL.createObjectURL(event.target.files[0]);
};







 
//edit guide
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var image      = $(this).data('image')
	var link      = $(this).data('link')
	
	$('.item_name').text(name)
	$("input[name='edit_guide_id']").val(id)
	$("input[name='edit_guide_name']").val(name)
	$("input[name='edit_guide_link']").val(link)

	var url =  '{{ url("uploads/magazine/guides/") }}/' + image
	$('.test').attr('src',url);

})


$('.upd').on('click',function(){
	$('.update').click();
})

$('.save').on('click',function(){
	$('.submit').click();
})




//edit gallary
$('.edit0').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var image      = $(this).data('image')
	
	$('.item_name').text(name)
	$("input[name='edit_gallary_id']").val(id)
	$("input[name='edit_gallary_name']").val(name)

	var url =  '{{ url("uploads/gallary/avatar/") }}/' + image
	$('.test0').attr('src',url);

})


$('.upd0').on('click',function(){
	$('.update0').click();
})

$('.save0').on('click',function(){
	$('.submit0').click();
})

//social

$(document).on('click','.add_preparation',function(){
$('.preparation').append(
	`
	<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
		<div class="row">
		<div class="col-sm-3" style="padding: 0 15px 0 3px">
										<input type="text" name="social_name[]"  class="form-control" placeholder="الاسم"  required>
									</div>

									<div class="col-sm-4" style="padding: 0 15px 0 3px">
										<input type="text" name="social_icon[]" class="form-control" placeholder="الصورة" required>
									</div>

									<div class="col-sm-4" style="padding: 0 15px 0 3px">
										<input type="text" name="social_link[]" class="form-control" placeholder="اللنك"  required>
									</div>


			<div class="col-sm-1" style="padding: 0 0 0 1px">
				<button type="button" class="btn btn-danger btn-block remove_preparation" data-code="${Date.now()}">
					<i class="fas fa-minus-circle"></i>
				</button>
			</div>
		</div>
	</div>
	`
);
})

$(document).on('click','.remove_preparation',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

//contacts

$(document).on('click','.add_mobiles',function(){
	$('.mobiles').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="mobiles[]" class="form-control" placeholder="الموبايل" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_mobiles" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_mobiles',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_faxs',function(){
	$('.faxs').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="faxs[]" class="form-control" placeholder="الفاكس" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_faxs" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_faxs',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.add_phones',function(){
	$('.phones').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="phones[]" class="form-control" placeholder="الهاتف الارضي" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_phones" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_phones',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})


$(document).on('click','.remove_emails',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$('.remove_emails').click(function() {
		$(this).parent().parent().parent().remove();
});

$(document).on('click','.add_emails',function(){
	$('.emails').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="emails[]" class="form-control" placeholder="البريد" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_emails" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})


$(document).on('click','.add_local',function(){
		$('.loc').append(
			`
			<div class="col-sm-12 father${Date.now()}" style="margin-top:20px">
				<div class="row">
                    <div class="col-sm-4" style="padding: 0 15px 0 3px">
                        <input type="text" name="loca[]" class="form-control" placeholder=" العنوان" >
                    </div>
                    
                    <div class="col-sm-3" style="padding: 0 15px 0 3px">
                        <input type="text" name="lat[]" class="form-control" placeholder=" lat" >
                    </div>
                    
                    <div class="col-sm-3" style="padding: 0 15px 0 3px">
                        <input type="text" name="long[]" class="form-control" placeholder=" long">
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

<script>

//map

$("#pac-input").focusin(function() {
    $(this).val('');
});


// This example adds a search box to a map, using the Google Place Autocomplete
// feature. People can enter geographical searches. The search box will return a
// pick list containing a mix of places and predicted search terms.

// This example requires the Places library. Include the libraries=places
// parameter when you first load the API. For example:
// <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initAutocomplete() {

	var pos = {lat:   {{ $magazine->latitude }} ,  lng: {{ $magazine->longitude }} };

	map = new google.maps.Map(document.getElementById('map'), {
		zoom: 15,
		center: pos
	});

	infoWindow = new google.maps.InfoWindow;
	geocoder = new google.maps.Geocoder();
	// move pin and current location
	infoWindow = new google.maps.InfoWindow;
	geocoder = new google.maps.Geocoder();

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
	$("#pac-input").val("{{ $magazine->address }} ");
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

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EG
 async defer"></script>


 
@endsection


