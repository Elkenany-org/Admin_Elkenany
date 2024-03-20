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
<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-body">
<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

	{{-- edit --}}
	<li class="nav-item">
		<a class="nav-link active" id="custom-content-below-store-tab" data-toggle="pill" href="#custom-content-below-store" role="tab" aria-controls="custom-content-below-store" aria-selected="true">بيانات الاسم</a>
	</li>

	{{-- images --}}
	<li class="nav-item">
		<a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">صور الاسم</a>
	</li>


</ul>
</div>
<div class="tab-content" id="custom-content-below-tabContent">
{{-- edit --}}
<div class="tab-pane fade show active" id="custom-content-below-store" role="tabpanel" aria-labelledby="custom-content-below-store-tab">
	<div class="row">
		<div class="col-sm-12">
			<div class="card-header">
			<h6 class="m-0" style="display: inline;">تعديل إسم تجاري :<span class="text-primary"> {{$name->name}} </span></h6>
			</div>
			<div class="card-body">
				<form action="{{route('updatenames')}}" method="post" enctype="multipart/form-data">
					<div class="row">
						{{csrf_field()}}
						<input type="hidden" name="id" value="{{$name->id}}">
					
						<div class="col-sm-12">
							<div class="row">
								{{-- details --}}
								{{-- title --}}
								<div class="col-sm-12"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> الاسم: <span class="text-danger">*</span></label>
										<input type="text" name="name" class="form-control" value="{{$name->name}}" placeholder=" الاسم" required="">
									</div>
								</div>
								{{-- desc --}}
								<div class="col-sm-12"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> المادة الفعالة: <span class="text-danger">*</span></label>
										<input type="text" name="desc" class="form-control" value="{{$name->desc}}" placeholder=" المادة الفعالة" required="">
									</div>
								</div>
								{{-- dose --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> الجرعة : <span class="text-danger">*</span></label>
										<input type="text" name="dose" class="form-control" value="{{$name->dose}}" placeholder=" الجرعة" required="">
									</div>
								</div>
								{{-- size --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">الحجم : <span class="text-danger">*</span></label>
										<input type="text" name="size" class="form-control" value="{{$name->size}}" placeholder="الحجم" required="">
									</div>
								</div>
								{{-- price --}}
								<div class="col-sm-6"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">السعر : <span class="text-danger">*</span></label>
										<input type="text" name="price" class="form-control" value="{{$name->price}}" placeholder="السعر" required="">
									</div>
								</div>

								<div class="col-sm-6 comp"  style="margin-top: 10px;">
														<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
														<input type="search" class="form-control company_search" name="company_search">
													</div>

								<div class="col-sm-12 " style="margin-top:20px">
									<div class="row company_search_result">

										<div class="col-sm-2 result_style">
											
												<input  style="display: inline;width: 50%;" type="radio" checked name="company_id" value="{{ $name->Company->id }}">
												<label class="text-info">{{ $name->Company->name }}</label>
											
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
		</div>
	</div>
</div>

{{-- images --}}
<div class="tab-pane fade" id="custom-content-below-images" role="tabpanel" aria-labelledby="custom-content-below-images-tab">
	<div class="row">
		<div class="col-sm-12">
			<form action="{{route('storeImagesnames')}}" method="post" enctype="multipart/form-data">
				{{csrf_field()}}
				<input type="hidden" name="id" value="{{$name->id}}">
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
							@foreach($name->Comnameimages as $key => $value)
							<div class="filtr-item image col-sm-2"  style="position: relative;display: inline-block;" data-category="1" data-sort="white sample">
								<input type="hidden" name="idd" value="{{$value->id}}">
								<button type="button" data-id="{{$value->id}}" class="btn btn-danger btn-sm del close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 5px 10px;color: #fff;border-radius: 50%;top: 30px;right: 20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									
								<img src="{{asset('uploads/sections/'.$value->image)}}" class="img-fluid mb-2  bounceIn" alt="black sample"/>
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
        url: "{{route('DeleteImagenames')}}",
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


