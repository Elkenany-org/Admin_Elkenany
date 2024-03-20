@extends('layouts.app')

@section('content')
<div class="container-fluid">
<div class="card card-primary card-outline">

          <div class="card-body">
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

            	{{-- main settings --}}
              <li class="nav-item">
                <a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-setting" role="tab" aria-controls="custom-content-below-home" aria-selected="true">الإعدادات الأساسيه</a>
              </li>

               {{-- about app --}}
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-about" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">عن التطبيق</a>
              </li>

              {{-- email and sms --}}
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-messages-tab" data-toggle="pill" href="#custom-content-below-messages" role="tab" aria-controls="custom-content-below-messages" aria-selected="false">الإيميل والرسائل</a>
              </li>

               {{-- notifications --}}
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-settings-tab" data-toggle="pill" href="#custom-content-below-settings" role="tab" aria-controls="custom-content-below-settings" aria-selected="false">الإشعارات</a>
			  </li>

			  {{-- social --}}
              <li class="nav-item">
                <a class="nav-link" id="custom-content-below-social-tab" data-toggle="pill" href="#custom-content-below-social" role="tab" aria-controls="custom-content-below-social" aria-selected="false">التواصل الاجتماعي للدليل</a>
			  </li>
			  


            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">

            	{{-- main settings --}}
              <div class="tab-pane fade show active" id="custom-content-below-setting" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
				<div class="row" style="margin-top: 10px">
					<!-- main setting -->
					<div class="col-md-6" style="border-left: 1px solid #cdcdcd">
						<div class="panel-body">
							<div class="card card-primary card-outline">
								<div class="card-header">
				                	<h5 class="m-0">إعدادات التطبيق<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
				              	</div>
				              	<div class="card-body">
									<form action="{{route('updatemainsetting')}}" method="post"  enctype="multipart/form-data">
										{{csrf_field()}}
										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">الاسم :</label>
											</div>
											<div class="col-lg-8">
							                    <input type="text" name="name" class="form-control" value="{{$setting->name}}" id="exampleInputName" required="">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">البريد الإلكتروني :</label>
											</div>
											<div class="col-lg-8">
							                    <input type="text" name="email" class="form-control" value="{{$setting->email}}" id="exampleInputEmail">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">رقم الجوال:</label>
											</div>
											<div class="col-lg-8">
							                    <input type="text" name="phone" class="form-control" value="{{$setting->phone}}" id="exampleInputPhone">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label"> الصورة للرئيسية :</label>
											</div>
											<div class="col-lg-8">
							                    <input type="file" name="image" class="form-control" value="{{$setting->image}}" id="exampleInputEmail">
											</div>
										</div>

										<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
									</form>
				              	</div>
				            </div>
						</div>
						{{--warning--}}
						<div class="modal fade" id="modal-secondary">
							<div class="modal-dialog">
							<div class="modal-content bg-secondary">
								<div class="modal-body">
								<p>هذه الصفحة خاصة بإعدادات التطبيق</p>
								</div>
							</div>
							</div>
						</div>
					</div>

					{{-- copyrigth --}}
					<div class="col-md-6">
						<div class="panel-body">

							<div class="card card-primary card-outline">
								<div class="card-header">
				                	<h5 class="m-0">شروط الاستخدام</h5>
				              	</div>
				              	<div class="card-body">
									<form action="{{route('updatecopyrigth')}}" method="post"  enctype="multipart/form-data" novalidate>
										{{csrf_field()}}
										<div class="form-group">
						                    <label for="exampleInputCopyright">شروط الاستخدام</label>
						                    <textarea class="form-control " rows="8" name="copyrigth"  id="full-featured-non-premium" rows="10">{{$setting->copyrigth}}</textarea>
						                </div>
										<div class="form-group">
						                    <label for="exampleInputCopyright">اتفاقية الخصوصية </label>
						                    <textarea class="form-control " rows="8" name="tagged"  id="full-featured-non-premium" rows="10">{{$setting->tagged}}</textarea>
						                </div>
					                	<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
									</form>
				              	</div>
				            </div>

						</div>
					</div>

				</div>
              </div>

              {{-- about app --}}
              <div class="tab-pane fade" id="custom-content-below-about" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
  					<div class="panel-body">

							<div class="card card-primary card-outline" style="margin-top: 10px">
								<div class="card-header">
				                	<h5 class="m-0">عن التطبيق</h5>
				              	</div>
				              	<div class="card-body">
									<form action="{{route('updateaboutapp')}}" method="post" enctype="multipart/form-data" novalidate>
										{{csrf_field()}}
										<div class="row">

											<div class="col-sm-6">
												<div class="form-group">
								                    <label for="exampleInputCopyrightAr">  من نحن</label>
								                    <textarea class="form-control " rows="8" name="about_ar"  id="basic-example" rows="10" required="">{{$setting->about_ar}}</textarea>
								                </div>
											</div>

											<div class="col-sm-6">
												<div class="form-group">
								                    <label for="exampleInputCopyrightEn">من التطبيق انجليزي</label>
								                    <textarea class="form-control " rows="8" name="about_en" id="full-featured" rows="10" >{{$setting->about_en}}</textarea>
								                </div>
											</div>

										</div>

						                <button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
									</form>
				              	</div>
				            </div>

					</div>
              </div>

              {{-- email and sms --}}
              <div class="tab-pane fade" id="custom-content-below-messages" role="tabpanel" aria-labelledby="custom-content-below-messages-tab">
				<div class="row" style="margin-top: 10px">

					<!-- smtp -->
					<div class="col-md-6" style="border-left: 1px solid #cdcdcd">
						<div class="panel-body">
							<div class="card card-primary card-outline">
								<div class="card-header">
				                	<h5 class="m-0">إعدادات الإيميل</h5>
				              	</div>
				              	<div class="card-body">
									<form action="{{route('updatesmtp')}}" method="post">
										{{csrf_field()}}
										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">النوع :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_type" value="{{$configration->smtp_type}}" placeholder="النوع" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">اسم المستخدم :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_username" value="{{$configration->smtp_username}}" placeholder="اسم المستخدم" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">الرقم السرى :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_password" value="{{$configration->smtp_password}}" placeholder="الرقم السرى" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">الايميل المرسل :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_sender_email" value="{{$configration->smtp_sender_email}}" placeholder="الايميل المرسل" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class=" control-label">الاسم المرسل :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_sender_name" value="{{$configration->smtp_sender_name}}" placeholder="الاسم المرسل" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">البورت :</label>
											</div>
											<div class="col-lg-8">
												<input type="number" name="smtp_port" value="{{$configration->smtp_port}}" placeholder="البورت" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class="control-label">الهوست :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" name="smtp_host" value="{{$configration->smtp_host}}" placeholder="الهوست" class="form-control">
											</div>
										</div>

										<div class="form-group row">
											<div class="col-lg-4">
												<label class=" control-label">التشفير :</label>
											</div>
											<div class="col-lg-8">
												<input type="text" value="{{$configration->smtp_encryption}}" name="smtp_encryption" placeholder="التشفير" class="form-control">
											</div>
										</div>

										<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
									</form>
				              	</div>
							</div>
						</div>
					</div>

					{{-- sms --}}
					<div class="col-md-6">
						<div class="panel-body">

							<div class="card card-primary card-outline">
				              <div class="card-header">
				                <h5 class="m-0">إعدادات الرسائل</h5>
				              </div>
				              <div class="card-body">
								<form action="{{route('updatesms')}}" method="post">
									{{csrf_field()}}
									<div class="form-group row">
										<div class="col-lg-4">
											<label class="control-label">رقم الهاتف :</label>
										</div>
										<div class="col-lg-8">
											<input type="number" name="sms_number" value="{{$configration->sms_number}}" placeholder="رقم الهاتف" class="form-control">
										</div>
									</div>

									<div class="form-group row">
										<div class="col-lg-4">
											<label class="control-label">الرقم السرى :</label>
										</div>
										<div class="col-lg-8">
											<input type="text" name="sms_password" value="{{$configration->sms_password}}" placeholder="الرقم السرى" class="form-control">
										</div>
									</div>

									<div class="form-group row">
										<div class="col-lg-4">
											<label class=" control-label">اسم الراسل :</label>
										</div>
										<div class="col-lg-8">
											<input type="text" value="{{$configration->sms_sender_name}}" name="sms_sender_name" placeholder="اسم الراسل " class="form-control">
										</div>
									</div>

									<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
								</form>
				              </div>
				            </div>

						</div>
					</div>

				</div> 
              </div>
				{{-- social --}}
				<div class="tab-pane fade" id="custom-content-below-social" role="tabpanel" aria-labelledby="custom-content-below-social-tab">
					<div class="row" style="margin-top: 10px">
						<!-- main setting -->
						<div class="col-md-12">
							<div class="panel-body">
								<div class="card card-primary card-outline">
									<div class="card-header">
									<h5 class="m-0" style="display: inline;"> قائمة  مواقع التواصل للدليل</h5>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-social" style="float: left;">
										إضافة موقع 
										<i class="fas fa-plus"></i>
									</button>
									</div>
									<div class="card-body">
									<table id="example1" class="table table-bordered table-hover table-striped">
										<thead>
										<tr>
										<th>#</th>
										<th>الصورة</th>
										<th>إسم الموقع</th>
										<th>التاريخ</th>
										<th>التحكم</th>
										</tr>
										</thead>
										<tbody>
										@foreach($social as $key => $value)
											<tr>
											<td>{{$key+1}}</td>
											<td><img src="{{$value->social_icon}}" style="width:50px"></td>
											<td>{{$value->social_name}}</td>
											<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
											<td>
												<a href="" 
												class="btn btn-info btn-sm edit_media"
												data-toggle="modal"
												data-target="#modal-media"
												data-id    = "{{$value->id}}"
												data-name  = "{{$value->social_name}}"
												data-icon  = "{{$value->social_icon}}"
												>  تعديل <i class="fas fa-edit"></i></a>
												<form action="{{route('Deletesocial')}}" method="post" style="display: inline-block;">
													{{csrf_field()}}
													<input type="hidden" name="social_id" value="{{$value->id}}">
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


					{{-- add social modal --}}
					<div class="modal fade" id="modal-social">
						<div class="modal-dialog">
						<div class="modal-content bg-primary">
							<div class="modal-header">
							<h4 class="modal-title">إضافة موقع  جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('Storesocial')}}" method="post">
									{{csrf_field()}}
									<label>إسم الموقع</label> <span class="text-danger">*</span>
									<input type="text" name="social_name" class="form-control" placeholder="إسم الموقع " required="" style="margin-bottom: 10px">
									<label>الصوره (url) </label> <span class="text-danger">*</span>
									<input type="text" name="social_icon" class="form-control" placeholder="url" required="" style="margin-bottom: 10px">
									<button type="submit" id="submit1" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light social">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>


					{{-- edit social modal --}}
					<div class="modal fade" id="modal-media">
						<div class="modal-dialog">
						<div class="modal-content bg-info">
							<div class="modal-header">
							<h4 class="modal-title">تعديل الموقع : <span class="item_name1"></span></h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							</div>
							<div class="modal-body">
							<form action="{{route('socialUpdate')}}" method="post">
									{{csrf_field()}}
									<input type="hidden" name="edit_social_id" value="">
									<label>إسم القسم</label> <span class="text-danger">*</span>
									<input type="text" name="edit_social_name" class="form-control" required="" style="margin-bottom: 10px">
									<label>الصوره (url) </label> <span class="text-danger">*</span>
									<input type="text" name="edit_social_icon" class="form-control" placeholder="url" required="" style="margin-bottom: 10px">
									<button type="submit" id="update1" style="display: none;"></button>
							</form>
							</div>
							<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light media">تحديث</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
							</div>
						</div>
						</div>
					</div>
				

		              {{-- notifications --}}
		              <div class="tab-pane fade" id="custom-content-below-settings" role="tabpanel" aria-labelledby="custom-content-below-settings-tab">
						<div class="row" style="margin-top: 10px">
							<!-- main setting -->
							<div class="col-md-6" style="border-left: 1px solid #cdcdcd">
								<div class="panel-body">
									<div class="card card-primary card-outline">
										<div class="card-header">
						                	<h5 class="m-0">one signal</h5>
						              	</div>
						              	<div class="card-body">
											<form action="{{route('updateonesignal')}}" method="post">
												{{csrf_field()}}
												<div class="form-group row">
													<div class="col-lg-4">
														<label class="control-label">application ID :</label>
													</div>
													<div class="col-lg-8">
														<input type="text" name="oneSignal_application_id" value="{{$configration->oneSignal_application_id}}" placeholder="application ID" class="form-control" >
													</div>
												</div>

												<div class="form-group row">
													<div class="col-lg-4">
														<label class="control-label">authorization :</label>
													</div>
													<div class="col-lg-8">
														<input type="text" name="oneSignal_authorization" value="{{$configration->oneSignal_authorization}}" placeholder="authorization" class="form-control" >
													</div>
												</div>

												<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
											</form>
						              	</div>
									</div>
								</div>
							</div>

						
							{{-- sms --}}
							<div class="col-md-6">
								<div class="panel-body">

									<div class="card card-primary card-outline">
						              <div class="card-header">
						                <h5 class="m-0">FCM</h5>
						              </div>
						              <div class="card-body">
										<form action="{{route('updatefcm')}}" method="post">
											{{csrf_field()}}
											<div class="form-group row">
												<div class="col-lg-4">
													<label class="control-label">server key :</label>
												</div>
												<div class="col-lg-8">
													<input type="text" name="fcm_server_key" value="{{$configration->fcm_server_key}}" placeholder=" server key" class="form-control">
												</div>
											</div>

											<div class="form-group row">
												<div class="col-lg-4">
													<label class="control-label">sender id :</label>
												</div>
												<div class="col-lg-8">
													<input type="text" name="fcm_sender_id" value="{{$configration->fcm_server_key}}" placeholder="sender id" class="form-control">
												</div>
											</div>

											<button class="btn btn-outline-primary" type="submit" style="margin-top: 10px">تحديث</button>
										</form>
						              </div>
						            </div>

								</div>
							</div>

						</div> 
					  </div>

		            
		            </div>

		          </div>
		          <!-- /.card -->
				</div>

		
        {{-- add modal --}}
		<div class="modal fade" id="modal-primary">
			<div class="modal-dialog">
			  <div class="modal-content bg-primary">
				<div class="modal-header">
				  <h4 class="modal-title">إضافة قيمة جديدة</h4>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
				  <form action="{{route('storedynamicsetting')}}" method="post">
						{{csrf_field()}}
						<div class="row">
							<div class="col-sm-6">
								<label>المفتاح</label> <span class="text-danger">*</span>
								<input type="text" name="key" class="form-control" placeholder="إسم المفتاح " required="" >
							</div>
							<div class="col-sm-6">
								<label> القيمة </label> <span class="text-danger">*</span>
								<input type="text" name="value" class="form-control" placeholder="القيمة" required="" >
							</div>
						</div>
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
	
	
		
		  {{-- edit modal --}}
		<div class="modal fade" id="modal-update">
			<div class="modal-dialog">
				<div class="modal-content bg-info">
				<div class="modal-header">
					<h4 class="modal-title">تعديل قيمة : <span class="item_name"></span></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form action="{{route('updatedynamicsetting')}}" method="post">
						{{csrf_field()}}
						<input type="hidden" type="text" name="edit_id" value="">
						<div class="row">
							<div class="col-sm-6">
								<label>المفتاح</label> <span class="text-danger">*</span>
								<input type="text" name="edit_key" class="form-control" placeholder="إسم المفتاح " required="" >
							</div>
							<div class="col-sm-6">
								<label> القيمة </label> <span class="text-danger">*</span>
								<input type="text" name="edit_value" class="form-control" placeholder="القيمة" required="" >
							</div>
						</div>
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
	

</div>
@endsection

@section('script')

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>

tinymce.init({
  selector: 'textarea#basic-example',
  height: 500,
  menubar: false,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste code help wordcount'
  ],
  toolbar: 'undo redo | formatselect | ' +
  'bold italic backcolor | alignleft aligncenter ' +
  'alignright alignjustify | bullist numlist outdent indent | ' +
  'removeformat | help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
  selector: 'textarea#full-featured-non-premium',
  plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
  toolbar_sticky: true,
  autosave_ask_before_unload: true,
  autosave_interval: '30s',
  autosave_prefix: '{path}{query}-{id}-',
  autosave_restore_when_empty: false,
  autosave_retention: '2m',
  image_advtab: true,
  link_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
  ],
  importcss_append: true,
  file_picker_callback: function (callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
    }
  },
  templates: [
        { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
  ],
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: 600,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: 'mceNonEditable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table',
  skin: useDarkMode ? 'oxide-dark' : 'oxide',
  content_css: useDarkMode ? 'dark' : 'default',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
 });

</script>
<script type="text/javascript">
    // add 
    $('.save').on('click',function(){
        $('#submit').click();
	})
	
    // update 
    $('.update').on('click',function(){
        $('#update').click();
    })

    //edit 
    $('.edit').on('click',function(){
        var id        = $(this).data('id')
        var key       = $(this).data('key')
        var value     = $(this).data('value')
        
        $('.item_name').text(key)
        $("input[name='edit_id']")    .val(id)
        $("input[name='edit_key']")   .val(key)
        $("input[name='edit_value']") .val(value)
    })

	// add social
	$('.social').on('click',function(){
        $('#submit1').click();
    })

    //edit social
    $('.edit_media').on('click',function(){
        var id         = $(this).data('id')
        var name       = $(this).data('name')
        var icon       = $(this).data('icon')
        
        $('.item_name1').text(name)
        $("input[name='edit_social_id']").val(id)
        $("input[name='edit_social_name']").val(name)
        $("input[name='edit_social_icon']").val(icon)
    })

    // update social
    $('.media').on('click',function(){
        $('#update1').click();
    })

</script>
@endsection