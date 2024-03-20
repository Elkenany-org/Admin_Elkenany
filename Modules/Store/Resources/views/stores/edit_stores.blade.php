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
						<a class="nav-link active" id="custom-content-below-store-tab" data-toggle="pill" href="#custom-content-below-store" role="tab" aria-controls="custom-content-below-store" aria-selected="true">بيانات الاعلان</a>
					</li>

					{{-- images --}}
					<li class="nav-item">
						<a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور الاعلان</a>
					</li>

					{{-- comments --}}
					<li class="nav-item">
						<a class="nav-link" id="custom-content-below-comments-tab" data-toggle="pill" href="#custom-content-below-comments" role="tab" aria-controls="custom-content-below-comments" aria-selected="false"> التعليقات</a>
					</li>


				</ul>
			</div>
			<div class="tab-content" id="custom-content-below-tabContent">
				{{-- edit --}}
				<div class="tab-pane fade show active" id="custom-content-below-store" role="tabpanel" aria-labelledby="custom-content-below-store-tab">
					<div class="row">
						<div class="col-sm-12">
							<div class="card-header">
								<h6 class="m-0" style="display: inline;">تعديل الاعلان :<span class="text-primary"> {{$store->title}} </span></h6>
							</div>
							<div class="card-body">
								<form action="{{route('Updatestoreads')}}" method="post" enctype="multipart/form-data">
									<div class="row">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$store->id}}">
										<div class="col-sm-6">
											<div class="row">
												{{-- desc --}}
												<div class="col-sm-12"style="margin-top: 20px;">
													<label class="text-primary">التفاصيل <span class="text-danger">*</span></label>
													<textarea class="form-control" rows="12" name="desc" value="{{$store->desc}}" placeholder="التفاصيل" required>{{$store->desc}}</textarea>
												</div>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="row">
												{{-- details --}}
												{{-- title --}}
												<div class="col-sm-12"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">عنوان الاعلان: <span class="text-danger">*</span></label>
														<input type="text" name="title" class="form-control" value="{{$store->title}}" placeholder="عنوان الاعلان" required="">
													</div>
												</div>
												{{-- address --}}
												<div class="col-sm-12"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">موقع الاعلان: <span class="text-danger">*</span></label>
														<input type="text" name="address" class="form-control" value="{{$store->address}}" placeholder="موقع الاعلان" required="">
													</div>
												</div>
												{{-- phone --}}
												<div class="col-sm-6"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
														<input type="text" name="phone" class="form-control" value="{{$store->phone}}" placeholder="رقم الهاتف " required="">
													</div>
												</div>
												{{-- salary --}}
												<div class="col-sm-6"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">السعر : <span class="text-danger">*</span></label>
														<input type="number" name="salary" class="form-control" value="{{$store->salary}}" placeholder="السعر" required="">
													</div>
												</div>
												{{-- sections --}}
												<div class="col-sm-6"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">  الاقسام <span class="text-danger">*</span></label>
														<select name="section_id" class="form-control section_id" required>
															<option value="" disabled selected>إختيار قسم</option>
															@foreach($sections as $value)
																<option value="{{$value->id}}"{{ isset($store) && $store->section_id == $value->id ? 'selected'  :'' }}>{{$value->name}}</option>
															@endforeach
														</select>
													</div>
												</div>
												{{-- contact --}}
												<div class="col-sm-6"style="margin-top: 20px;">
													<div class="from-group">
														<label class="text-primary">  نوع التواصل <span class="text-danger">*</span></label>
														<select name="con_type" class="form-control" required>
															<option value="" disabled selected>إختيار نوع</option>
															<option value="الرسائل"{{ isset($store) && $store->con_type == 'الرسائل' ? 'selected'  :'' }}>الرسائل</option>
															<option value="الموبايل"{{ isset($store) && $store->con_type == 'الموبايل' ? 'selected'  :'' }}>الموبايل</option>
															<option value="كلاهما"{{ isset($store) && $store->con_type == 'كلاهما' ? 'selected'  :'' }}>كلاهما</option>
														</select>
													</div>
												</div>
											</div>

										</div>

										{{-- approved or not--}}
										<div class="col-sm-6"style="margin-top: 20px;">

											<div class="from-group">
												<label class="text-primary">  الحالة <span class="text-danger">*</span></label>
												<div class="custom-control custom-radio" style="margin-top: 10px">
													<input type="radio" id="customRadio1" name="approved" value="1" @if($store->approved == '1') checked @endif class="custom-control-input">
													<label class="custom-control-label" for="customRadio1">مقبول</label>
												</div>
												<div class="custom-control custom-radio">
													<input type="radio" id="customRadio2" name="approved" value="0" @if($store->approved == '0') checked @endif class="custom-control-input">
													<label class="custom-control-label" for="customRadio2"> غير مقبول
													</label>
													<input type="text" name="message" class="form-control" value="{{$store->message}}" placeholder="سبب عدم القبول" >
												</div>
											</div>
										</div>

									</div>						{{-- submit --}}

									<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px;  " class="btn btn-outline-primary btn-block">حفظ</button>
								</form>
							</div>
						</div>
					</div>
				</div>

				{{-- images --}}
				<div class="tab-pane fade" id="custom-content-below-images" role="tabpanel" aria-labelledby="custom-content-below-images-tab">
					<div class="row">
						<div class="col-sm-12">
							<form action="{{route('storeImagesstore')}}" method="post" enctype="multipart/form-data">
								{{csrf_field()}}
								<input type="hidden" name="id" value="{{$store->id}}">
								<div class="card-header">
									<h5 class="m-0" style="display: inline;" style="float: right">اضافة صور</h5>
									<div class="btn btn-primary" style="float: left;height:36px;padding:3px;">
										<input type="file" name="image[]" id="gallery"  style="display: none;" accept="image/*" multiple>
										<label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
									</div>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-sm-12 marbo img">
											<div class="gallery">
												@foreach($store->StoreAdsimages as $key => $value)
													<div class="filtr-item image col-sm-2"  style="position: relative;display: inline-block;" data-category="1" data-sort="white sample">
														<input type="hidden" name="idd" value="{{$value->id}}">
														<button type="button" data-id="{{$value->id}}" class="btn btn-danger btn-sm del close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 5px 10px;color: #fff;border-radius: 50%;top: 30px;right: 20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>

														<img src="{{asset('uploads/stores/alboum/'.$value->image)}}" class="img-fluid mb-2  bounceIn" alt="black sample"/>
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
								<button style="width: 50%; margin-left: auto;margin-top:20px; margin-right: auto; margin-bottom:30px;  " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
							</form>
						</div>
					</div>
				</div>

				{{-- comments --}}
				<div class="tab-pane fade"  id="custom-content-below-comments" role="tabpanel" aria-labelledby="custom-content-below-comments-tab">
					<div class="row">
						<div class="col-sm-12">

							<div class="card-body">
								<table id="example1" class="table table-bordered table-hover table-striped">
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
									@foreach($store->StoreAdsComments as $key => $value)
										<tr>
											<td>{{$key+1}}</td>
											<td>{{$value->Customer->name}}</td>
											<td>{{$value->comment}}</td>
											<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
											<td>
												<form action="{{route('Deletecomments')}}" method="post" style="display: inline-block;">
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
@endsection

@section('script')
	<script type="text/javascript">
		$(".image").hover(function(){
					$(".del",this).css("display", "block") },
				function(){
					$(".del").css("display", "none");
				});
		$(".del").hover(function(){
					$(this).css("display", "block") },
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
						url: "{{route('deleteimagestore')}}",
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

		function ChooseAvatar1(){$("input[name='image[]']").click()}
	</script>
@endsection


