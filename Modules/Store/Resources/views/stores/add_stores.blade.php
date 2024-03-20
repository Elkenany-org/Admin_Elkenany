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
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">إضافة اعلان لمتجر جديد<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('Storestoreads')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<div class="row">
						<div class="col-sm-6">
							<div class="row">
								{{-- desc --}}
								<div class="col-sm-12"	style="margin-top: 20px;">
									<label class="text-primary">التفاصيل <span class="text-danger">*</span></label>
									<textarea class="form-control" rows="12" name="desc" value="{{old('desc')}}" placeholder="التفاصيل" required></textarea>
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
										<input type="text" name="title" class="form-control" value="{{old('title')}}" placeholder="عنوان الاعلان" required="">
									</div>
								</div>
								{{-- address --}}
								<div class="col-sm-12"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">موقع الاعلان: <span class="text-danger">*</span></label>
										<input type="text" name="address" class="form-control" value="{{old('address')}}" placeholder="موقع الاعلان" required="">
									</div>
								</div>
								{{-- phone --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
										<input type="number" name="phone" class="form-control" value="{{old('phone')}}" placeholder="رقم الهاتف " required="">
									</div>
								</div>
								{{-- salary --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">السعر : <span class="text-danger">*</span></label>
										<input type="number" name="salary" class="form-control" value="{{old('salary')}}" placeholder="السعر" required="">
									</div>
								</div>
								{{-- sections --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  الاقسام <span class="text-danger">*</span></label>
										<select name="section_id" class="form-control section_id" required>
										<option value="" disabled selected>إختيار قسم</option>
										@foreach($sections as $value)
											<option value="{{$value->id}}">{{$value->name}}</option>
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
										<option value="الرسائل">الرسائل</option>
										<option value="الموبايل">الموبايل</option>
										<option value="كلاهما">كلاهما</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						{{-- image --}}
						<div class="col-sm-12"style="margin-top: 20px;">
							<div class="card-header">
								<div class="btn btn-primary" style="float: left;height:36px;padding:3px;">
								<input type="file" name="image[]" id="gallery1"  style="display: none;" accept="image/*" multiple>
								<label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-sm-12 marbo img">
										<h5 class="m-0" style="display: inline; text-align: center;">قائمة صور  </h5>
										<div class="gallery1">
										
										</div>
									</div>
								</div>
							</div>
						</div>
						{{-- submit --}}

							<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px;  " class="btn btn-outline-primary btn-block">حفظ</button>

					</div>
            	</form>
            </div>
			{{--warning--}}
			<div class="modal fade" id="modal-secondary">
			<div class="modal-dialog">
			<div class="modal-content bg-secondary">
				<div class="modal-body">
				<p>هذه الصفحة خاصة   اعلان للمتجر</p>
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
function ChooseAvatar1(){$("input[name='image[]']").click()}	
	
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

    $('#gallery1').on('change', function() {
        imagesPreview(this, 'div.gallery1');
    });
});
</script>
@endsection


