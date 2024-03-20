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
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">إضافة إسم تجاري جديد<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('Storenames')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<div class="row">
					
						<div class="col-sm-12">
							<div class="row">
								{{-- details --}}
								{{-- name --}}
								<div class="col-sm-12"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> الاسم: <span class="text-danger">*</span></label>
										<input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder=" الاسم" required="">
									</div>
								</div>
								{{-- desc --}}
								<div class="col-sm-12"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> المادة الفعالة: <span class="text-danger">*</span></label>
										<input type="text" name="desc" class="form-control" value="{{old('desc')}}" placeholder=" المادة الفعالة" required="">
									</div>
								</div>
								{{-- dose --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> الجرعة : <span class="text-danger">*</span></label>
										<input type="text" name="dose" class="form-control" value="{{old('dose')}}" placeholder=" الجرعة" required="">
									</div>
								</div>
								{{-- size --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">الحجم : <span class="text-danger">*</span></label>
										<input type="text" name="size" class="form-control" value="{{old('size')}}" placeholder="الحجم" required="">
									</div>
								</div>
								{{-- price --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">السعر : <span class="text-danger">*</span></label>
										<input type="text" name="price" class="form-control" value="{{old('price')}}" placeholder="السعر" required="">
									</div>
								</div>
								{{-- company --}}
								<div class="col-sm-6 comp"  style="margin-top: 10px;">
									<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
									<input type="search" class="form-control company_search" name="company_search">
								</div>

								<div class="col-sm-12 " style="margin-top:20px">
									<div class="row company_search_result">
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
				<p>هذه الصفحة خاصة   الاسم التجاري</p>
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
	if ($('input[name="company_id"]').is(':checked')) { 
        $('input[name="company_id"]').not(':checked').parent().fadeOut().remove();
			$.each(s,function(k,v){
				if ($('input[name="company_id"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="radio" value="${v.id}"  class="company_id" name="company_id">
							<label class="text-info">${v.name}</label>
						</div>
					`);
				}
		})
	}else{
		$('.company_search_result').html('')
		$.each(s,function(k,v){
				if ($('input[name="company_id"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="radio" value="${v.id}"  class="company_id" name="company_id">
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


