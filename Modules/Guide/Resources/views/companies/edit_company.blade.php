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
					<a class="nav-link active" id="custom-content-below-company-tab" data-toggle="pill" href="#custom-content-below-company" role="tab" aria-controls="custom-content-below-company" aria-selected="true">بيانات الشركة</a>
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
					<a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور الشركة</a>
				</li>

				{{-- products --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-products-tab" data-toggle="pill" href="#custom-content-below-products" role="tab" aria-controls="custom-content-below-products" aria-selected="false">منتجات </a>
				</li>

				{{-- transports --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-transports-tab" data-toggle="pill" href="#custom-content-below-transports" role="tab" aria-controls="custom-content-below-transports" aria-selected="false">تكلفة الشحن </a>
				</li>


				{{-- local --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-local-tab" data-toggle="pill" href="#custom-content-below-local" role="tab" aria-controls="custom-content-below-local" aria-selected="false"> بورصات الشركة</a>
				</li>

			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">

			{{-- edit --}}
			<div class="tab-pane fade show active" id="custom-content-below-company" role="tabpanel" aria-labelledby="custom-content-below-company-tab">	
				<div class="row">
					<div class="col-sm-12">
							<div class="card-body">
								<form action="{{route('updatecompany')}}" method="post" enctype="multipart/form-data">
									<div class="row">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$companies->id}}">

										<div class="col-sm-12 marbo">
											<div class="row">
												<div class="col-sm-2">
													<label>الاسم <span class="text-danger">*</span></label>
													<input type="text" value="{{$companies->name}}" class="form-control" name="name" placeholder=" الاسم" required></input>
												</div>
												
												<div class="col-sm-2">
													<label>التلفون <span class="text-danger">*</span></label>
													<input type="text" value="{{$companies->manage_phone}}" class="form-control" name="manage_phone" placeholder=" التلفون" required></input>
												</div>
												<div class="col-sm-2">
													<label>الايميل <span class="text-danger">*</span></label>
													<input type="text" value="{{$companies->manage_email}}" class="form-control" name="manage_email" placeholder=" الايميل" required></input>
												</div>
												<div class="col-sm-3" style="margin-top: 10px">
													<label class="text-primary">  الدول <span class="text-danger">*</span></label>
													<select name="country_id" class="form-control country" required>
														<option value="" disabled selected>إختيار دولة</option>
														@foreach($countries as $value)
															<option value="{{$value->id}}" @if($companies->country_id == $value->id) selected @endif>{{$value->name}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-3" style="margin-top: 10px">
													<label class="text-primary"> المحافظة <span class="text-danger">*</span></label>
													<select name="city_id" class="form-control cities" required>
														@foreach($cities as $value)
																<option value="{{$value->id}}" @if($companies->city_id == $value->id) selected @endif>{{$value->name}}</option>
														@endforeach
													</select>
												</div>
												<div class="col-sm-2">
													<label>  النوع <span class="text-danger">*</span></label>
													<select name="paied" class="form-control" required>
														<option value="" disabled selected>إختيار </option>
														<option value="0"@if($companies->paied == '0') selected @endif>مجاني</option>
														<option value="1" @if($companies->paied == '1') selected @endif>مدفوع</option>
													</select>
												</div>
											</div>
										</div>

										{{-- majors --}}
										<div class="col-sm-12" style="margin-top: 10px">
											<label class="text-primary">الاقسام الرئيسية</label> <span class="text-danger">*</span><br>
											<div class="row">
												@foreach($sections as $value)
													<div class="col-sm-2">
														@if(in_array($value->id, $majs))
															<input type="checkbox"  class="major" checked name="sections[]" value="{{ $value->id }}"><span style="margin-right: 3px">{{ $value->name }}</span>
														@else
															<input type="checkbox"  class="major" name="sections[]" value="{{ $value->id }}"><span style="margin-right: 3px">{{ $value->name }}</span>
														@endif
													</div>
												@endforeach
											</div>
										</div>
										<div class="col-sm-4" >
											<label style="margin-top: 10px" class="text-primary">  ابحث عن قسم فرعي <span class="text-danger">*</span></label>
											<input type="search" class="form-control sec_search" name="sec_search">
										</div>
										{{-- sections --}}
										<div class="col-sm-12" style="margin-top: 10px">
											<label class="text-primary">الاقسام  الفرعية</label> <span class="text-danger">*</span><br>
											<div class="row sub_sections">
												@foreach($sectionss as $value)
													@if(in_array($value->id, $secs))
														<div class="col-sm-2 result_style sub{{$value->section_id}}">
															
																<input  style="display: inline;width: 50%;" type="checkbox" checked name="SubSections[]" value="{{ $value->id }}">
																<label class="text-info">{{ $value->name }}</label>
															
														</div>
													@endif
												@endforeach
											</div>
										</div>
										<div class="col-sm-6 marbo">
										<label>نبزة مختصرة <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="7" name="short_desc" placeholder="نبزة مختصرة " required>{{$companies->short_desc}}</textarea>
										</div> 
										<div class="col-sm-6 marbo">
										<label>عن الشركة <span class="text-danger">*</span></label>
											<textarea class="form-control" rows="7" name="about" placeholder="عن الشركة" required>{{$companies->about}}</textarea>
										</div>  
										<div class="col-sm-6 marbo">
											<label >إختيار صورة <span class="text-primary"> * </span></label><br>
											<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
											<img src="{{asset('uploads/company/images/'.$companies->image)}}" onclick="ChooseAvatar()" id="avatar">
										</div>
										<div class="col-sm-6 marbo">
										<label for="exampleInputEmail1">العنوان</label>

										<input type="text" id="pac-input" class="form-control" placeholder="  " required value="{{ $companies->address }}" name="address">
										<label for="exampleInputEmail1">lat</label>
										<input type="text" class="form-control"  placeholder=" lat" value="{{$companies->latitude}}" id="latitude" required name="latitude">
										<label for="exampleInputEmail1">long</label>
										<input type="text" class="form-control"  placeholder=" long" value="{{$companies->longitude}}" id="longitude"  required name="longitude">
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
				<form action="{{route('Updatecontact')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$companies->id}}">
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
					
							<form action="{{route('storesocial')}}" method="post" enctype="multipart/form-data">
							{{csrf_field()}}
							<input type="hidden" name="id" value="{{$companies->id}}">
							<div class="row">
							    @foreach($companies->CompanySocialmedia as $media)
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
					<form action="{{route('Updatelocal')}}" method="post" enctype="multipart/form-data">
						<div class="row">
							<div class="col-sm-2" style="padding: 0 2px 0px 0px; margin-bottom:20px;">
								<button type="button" class="btn btn-primary btn-block add_local">
									<i class="fas fa-plus"></i>
								</button>
							</div>
							
							
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$companies->id}}">
									@if(count($companies->Companyaddress) == 0)
									<div class="col-sm-12" style="margin-top:20px">
									
										<div class="row loc"  style="margin-top: 20px">

						
											<div class="col-sm-4" style="padding: 0 15px 0 3px">
												<input type="text" name="loca[]"  class="form-control" placeholder=" العنوان" >
											</div>
											
											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<input type="text" name="lat[]"  class="form-control" placeholder=" lat" >
											</div>
											
											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<input type="text" name="long[]" class="form-control" placeholder=" long" >
											</div>

											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<select name="type[]" class="form-control" placeholder="" >
													<option value="">اختر نوع العنوان</option>

													@foreach(config('constants.address_type') as $name => $val)
														<option value="{{$name}}" {{$value->type == $name ? 'selected' : ''}}>{{$val}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-sm-1">
											</div>
											<div class="col-sm-1" style="padding: 0 0 0 1px">
												<button type="button" class="btn btn-danger btn-block remove_quantiti">
													<i class="fas fa-minus-circle"></i>
												</button>
											</div>
											
											
										</div>
									</div>
									@endif
									@foreach($companies->Companyaddress as $key => $value)
									<div class="col-sm-12" style="margin-top:20px">
										<div class="row"  style="margin-top: 20px">


											<div class="col-sm-4" style="padding: 0 15px 0 3px">
												<input type="text" name="loca[]" value="{{ $value->address}}" class="form-control" placeholder=" العنوان" >
											</div>
											
											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<input type="text" name="lat[]" value="{{ $value->latitude}}" class="form-control" placeholder=" lat" >
											</div>
											
											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<input type="text" name="long[]"  value="{{ $value->longitude}}" class="form-control" placeholder=" long" >
											</div>
											<div class="col-sm-2" style="padding: 0 15px 0 3px">
												<select name="type[]" class="form-control" placeholder="" >
													<option value="">اختر نوع العنوان</option>

													@foreach(config('constants.address_type') as $name => $val)
														<option value="{{$name}}" {{$value->type == $name ? 'selected' : ''}}>{{$val}}</option>
													@endforeach
												</select>
											</div>

											<div class="col-sm-1">
											</div>

											<div class="col-sm-1" style="padding: 0 0 0 1px">
												<button type="button" class="btn btn-danger btn-block remove_quantiti">
													<i class="fas fa-minus-circle"></i>
												</button>
											</div>
											
											
										</div>
									</div>
									@endforeach
									<div class="col-sm-12 loc">
									</div>
								</div>
								<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-success btn-block">حفظ</button>
						</div>
					</form>
				</div>
			
			{{-- local --}}
			<div class="tab-pane fade"  id="custom-content-below-local" role="tabpanel" aria-labelledby="custom-content-below-local-tab">
				<div class="row">
					<div class="col-sm-12">
						<!-- /.card-header -->
						<div class="card-body">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
							<th>#</th>
							<th>إسم القسم</th>
							<th>القسم الرئيسي</th>
							<th>التاريخ</th>
							<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($companies->LocalStockMember as $key => $value)
								<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->Section->name}}</td>

								<td>{{$value->Section->name}}</td>
								<td> <span class="badge badge-success">{{Date::parse($value->Section->created_at)->diffForHumans()}}</span></td>
								<td>
								<a href="{{route('Editsection',$value->Section->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
								<a href="{{route('showMember',$value->Section->id)}}" class="btn btn-primary btn-sm " type="submit">  بيانات البورصة <i class="fas fa-eye"></i></a>
									<form action="{{route('deletelocalsection')}}" method="post" style="display: inline-block;">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$value->id}}">
										<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
									</form>
								</td>
								</tr>
							@endforeach
							</tfoot>
						</table>
						<!-- /.card-body -->
					</div>
					</div>
				</div>
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
									@foreach($companies->Companygallary as $key => $value)
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
											<a href="{{route('gallary',$value->id)}}" class="btn btn-primary btn-sm">  صور الالبوم <i class="fas fa-eye"></i></a>
											<form action="{{route('Deletegallary')}}" method="post" style="display: inline-block;">
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
									<form action="{{route('updategallary')}}" method="post" enctype="multipart/form-data">
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
								<form action="{{route('storegallary')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="company_id" value="{{$companies->id}}">
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

			{{-- products --}}
			<div class="tab-pane fade" id="custom-content-below-products" role="tabpanel" aria-labelledby="custom-content-below-products-tab">
				<div class="row">
					<div class="col-sm-12">
							<div class="card-header">
							<h5 class="m-0" style="display: inline;" style="float: right">المنتجات</h5>
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
									إضافة منتج 
									<i class="fas fa-plus"></i>
								</button>
							</div>
							<div class="card-body">
							<table id="example1" class="table table-bordered table-hover table-striped">
								<thead>
								<tr>
								<th>#</th>
								<th>الصورة</th>
								<th>إسم المنتج</th>
								<th>التاريخ</th>
								<th>التحكم</th>
								</tr>
								</thead>
								<tbody>
								@foreach($companies->Companyproduct as $key => $value)
									<tr>
									<td>{{$key+1}}</td>
									<td style="padding-top: 10px"><img alt="Avatar" class="table-avatar" src="{{asset('uploads/company/product/'.$value->image)}}" style="display: inline;width: 2.5rem;"></td>
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
										<form action="{{route('Deleteproduct')}}" method="post" style="display: inline-block;">
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

					{{-- edit product modal --}}
					<div class="modal fade" id="modal_edit">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title"> تعديل </h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('updateproduct')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_product_id" value="">
										<label>إسم المنتج</label> <span class="text-danger">*</span>
										<input type="text" name="edit_product_name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">
										<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
										<input type="file" name="edit_product_image" accept="image/*" onchange="loadAvatar3(event)" style="display: none;">
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

					{{-- add product modal --}}
					<div class="modal fade" id="modal-primary">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة منتج جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('storeproduct')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="product_id" value="{{$companies->id}}">
									<label>إسم المنتج</label> <span class="text-danger">*</span>
									<input type="text" name="product_name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">
									<label style="margin-top: 10px;" >إختيار صورة <span class="text-primary"> * </span></label></br>
									<input type="file" name="product_image" accept="image/*" onchange="loadAvatar2(event)" style="display: none;">
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
			
			{{-- transports --}}
			<div class="tab-pane fade" id="custom-content-below-transports" role="tabpanel" aria-labelledby="custom-content-below-transports-tab">
				<div class="row">
					<div class="col-sm-12">
						<div class="card-header">
						<h5 class="m-0" style="display: inline;" style="float: right">تكلفة الشحن</h5>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1" style="float: left;">
								إضافة تكلفة 
								<i class="fas fa-plus"></i>
							</button>
						</div>
						<div class="card-body">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
							<th>#</th>
							<th>إسم المنتج</th>
							<th>سعر المنتج</th>
							<th> المحافظة</th>
							<th>التاريخ</th>
							<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($companies->Companytransports as $key => $value)
								<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->product_name}}</td>
								<td>{{$value->price}}</td>
								<td>{{$value->City->name}}</td>
								<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
								<td>
									<a href="" 
									class="btn btn-info btn-sm edit1"
									data-toggle="modal"
									data-target="#modal_edit1"
									data-id    = "{{$value->id}}"
									data-name  = "{{$value->product_name}}"
									data-price = "{{$value->price}}"
									data-type  = "{{$value->product_type}}"
									data-city = "{{$value->city_id}}"
									>  تعديل <i class="fas fa-edit"></i></a>
									<form action="{{route('Deletetransport')}}" method="post" style="display: inline-block;">
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

					{{-- edit transport modal --}}
					<div class="modal fade" id="modal_edit1">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
								<div class="modal-header">
								<h4 class="modal-title"> تعديل </h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span></button>
								</div>
								<div class="modal-body">
								<form action="{{route('updatetransport')}}" method="post" enctype="multipart/form-data">
										{{csrf_field()}}
										<input type="hidden" name="edit_transport_id" value="">
										<label>إسم المنتج</label> <span class="text-danger">*</span>
										<input type="text" name="edit_transport_product_name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">
										<label>سعر المنتج</label> <span class="text-danger">*</span>
										<input type="number" step="any" name="edit_transport_product_price" class="form-control" placeholder="سعر المنتج " required="" style="margin-bottom: 10px">
										<label> المحافظة</label> <span class="text-danger">*</span>
										<select name="edit_city_id" class="form-control">
											@foreach($cities as $value)
												<option value="{{$value->id}}">{{$value->name}}</option>
											@endforeach
										</select>

										<label> النوع</label> <span class="text-danger">*</span>
										<select name="edit_transport_product_type" class="form-control">
												<option value="0">تكلفة نقل الكتكوت</option>
												<option value="1">تكلفة نقل العلف</option>
										</select>
								
										<button type="submit" class="update" id="submit" style="display: none;"></button>
								</form>
								</div>
								<div class="modal-footer justify-content-between">
								<button type="button" class="btn btn-outline-light upd1 save1">حفظ</button>
								<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
								</div>
							</div>
						</div>
					</div>

					{{-- add transport modal --}}
					<div class="modal fade" id="modal-primary1">
						<div class="modal-dialog">
							<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة تكلفة جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('storetransport')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="company_id" value="{{$companies->id}}">
									<label>إسم المنتج</label> <span class="text-danger">*</span>
									<input type="text" name="transport_product_name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">
									<label>سعر المنتج</label> <span class="text-danger">*</span>
									<input type="number" step="any" name="transport_product_price" class="form-control" placeholder="سعر المنتج " required="" style="margin-bottom: 10px">
									<label> المحافظة</label> <span class="text-danger">*</span>
									<select name="city_id" class="form-control">
										@foreach($cities as $value)
											<option value="{{$value->id}}">{{$value->name}}</option>
										@endforeach
									</select>

									<label> النوع</label> <span class="text-danger">*</span>
									<select name="transport_product_type" class="form-control">
											<option value="0">تكلفة نقل الكتكوت</option>
											<option value="1">تكلفة نقل العلف</option>
									</select>
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
@endsection

@section('script')
<script type="text/javascript">
// get sub sections
$(document).on('change','.major', function(){
	var id = $(this).val();
		if ($(this).is(':checked')) {
			
	var data = {
	section_id    : $(this).val(),
	_token        : $("input[name='_token']").val()
	}


		$.ajax({
		url     : "{{ url('get-sub-sections') }}",
		method  : 'post',
		data    : data,
		success : function(s,result){
			
    
			$.each(s,function(k,v){

			$('.sub_sections').append(`

				<div class="col-sm-2 result_style sub${id}">
					<input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
					<label class="text-info">${v.name}</label>
					
				</div>
			`);
		})
	
		}});
	}else{

		$('.sub'+id).fadeOut().remove();
	}
	});

	
// search companies
$(document).on('keyup','.sec_search', function(){
    var id = $('.major').val();
var data = {
    search     : $(this).val(),
    section_id : $('.major').val(),
    _token     : $("input[name='_token']").val()
}

$.ajax({
url     : "{{ url('get-sub-sections-guide-search') }}",
method  : 'post',
data    : data,
success : function(s,result){
    if ($('input[name="SubSections[]"]').is(':checked')) { 
        $('input[name="SubSections[]"]').not(':checked').parent().fadeOut().remove();
        $.each(s,function(k,v){
            if ($('input[name="SubSections[]"]:checked').val() != v.id) { 
    $('.sub_sections').append(`
                <div class="col-sm-2 result_style sub${id}">
                    <input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
                    <label class="text-info">${v.name}</label>
					
				</div>
    `);
}
})
    }else{
        $('.sub_sections').html('')
    $.each(s,function(k,v){
        if ($('input[name="SubSections[]"]:checked').val() != v.id) {
    $('.sub_sections').append(`
                <div class="col-sm-2 result_style sub${id}">
                    <input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
                    <label class="text-info">${v.name}</label>
					
				</div>
    `);
}
})}
}});

});

</script>
<script type="text/javascript">
//edit image
function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar2(){$("input[name='product_image']").click()}
var loadAvatar2 = function(event) {
var output = document.getElementById('avatar2');
output.src = URL.createObjectURL(event.target.files[0]);
};

function ChooseAvatar3(){$("input[name='edit_product_image']").click()}
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



 
//edit product
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var image      = $(this).data('image')
	
	$('.item_name').text(name)
	$("input[name='edit_product_id']").val(id)
	$("input[name='edit_product_name']").val(name)

	var url =  '{{ url("uploads/company/product/") }}/' + image
	$('.test').attr('src',url);

})


$('.upd').on('click',function(){
	$('.update').click();
})

$('.save').on('click',function(){
	$('.submit').click();
})


//edit transport
$('.edit1').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')
	var price      = $(this).data('price')
	var type      = $(this).data('type')
	var city      = $(this).data('city')
	
	$('.item_name').text(name)
	$("input[name='edit_transport_id']").val(id)
	$("input[name='edit_transport_product_name']").val(name)
	$("input[name='edit_transport_product_price']").val(price)
	$("select[name='edit_city_id'] > option").each(function() {
        if($(this).val() == city)
        {
          $(this).attr("selected","")
        }
      });
	$("select[name='edit_transport_product_type'] > option").each(function() {
		if($(this).val() == type)
		{
			$(this).attr("selected","")
		}
	});

})


$('.upd1').on('click',function(){
	$('.update').click();
})

$('.save1').on('click',function(){
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
                    
                    <div class="col-sm-2" style="padding: 0 15px 0 3px">
                        <input type="text" name="lat[]" class="form-control" placeholder=" lat" >
                    </div>
                    
                    <div class="col-sm-2" style="padding: 0 15px 0 3px">
                        <input type="text" name="long[]" class="form-control" placeholder=" long">
                    </div>
					<div class="col-sm-2" style="padding: 0 15px 0 3px">
							<select name="type[]" class="form-control" placeholder="" >
								<option value="">اختر نوع العنوان</option>
								@foreach(config('constants.address_type') as $name => $val)
								<option value="{{$name}}">{{$val}}</option>
								@endforeach
								</select>
							</div>
							<div class="col-sm-1">
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

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EG
 async defer"></script>


 
@endsection


