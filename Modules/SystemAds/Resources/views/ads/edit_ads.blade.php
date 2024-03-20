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
</style>
@endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">{{$ads->type}} <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('updateadsss')}}" method="post" enctype="multipart/form-data">

	            	<div class="row">
            			{{csrf_field()}}
            			<input type="hidden" name="id" value="{{$ads->id}}">

	            		{{-- avatar --}}
						<div class="col-sm-12" style="margin-bottom: 20px">
	            			<div class="from-group ">

	            				@if($ads->type == 'popup')

									@if( substr($ads->image, -3) == 'mp4')
										<video class="video" style="height:200px;width:45%;"controls >
											<source src="{{asset('uploads/ads/'.$ads->image)}}" onclick="ChooseAvatar()" id="video_here">
										</video>
									@else
										<img style="height:200px;width:50%;" src="{{asset('uploads/ads/'.$ads->image)}}"  onclick="ChooseAvatar()" id="avatar">
									@endif


								@else
								
								<img style="height:200px;width:50%;" src="{{asset('uploads/full_images/'.$ads->image)}}" onclick="ChooseAvatar()" id="avatar">
								@endif
									<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;" >
{{--									<img src="{{asset('dist/img/placeholder2.png')}}" onclick="ChooseAvatar()" id="avatar">--}}
							</div>
						</div>
						@if($ads->type != 'notification' && $ads->type != 'popup')
						<div class="col-sm-12" style="margin-bottom: 20px">
							<div class="from-group ">
								<label class="text-primary">نوع الاعلان: </label>
								@if($ads->sub == '1')
									<strong>فرعي</strong>
								@endif
								@if($ads->main == '1')
									<strong>رئيسي</strong>
								@endif
							</div>
						</div>

						<div class="col-sm-12" style="margin-bottom: 20px">
							<div class="from-group col-3" style="margin-bottom: 20px">
								<label class="text-primary">  مكان الاعلان</label> <span class="text-danger">*</span>

								<select class="form-control" name="system_ads_pages[type]" id="type_place" disabled>
									@foreach(Config('constants.type_place') as $key => $value)
										@if(isset($ads->SystemAdsPages) && count($ads->SystemAdsPages) > 0)
										<option value="{{$key}}" {{  $ads->SystemAdsPages[0]->type == $key ? 'selected' : '' }}>{{$value['name']}}</option>
										@else
											<option value="{{$key}}">{{$value['name']}}</option>
										@endif
									@endforeach
								</select>
							</div>
							<div class="from-group col-3" id="section_div" style="margin-bottom: 20px">
								<label class="text-primary">القطاع</label> <span class="text-danger">*</span>

								<select class="form-control" name="system_ads_pages[section_type]" disabled id="section">

								</select>
							</div>
							<div class="from-group col-3" style="margin-bottom: 20px" id="sec_section_div">
								<label class="text-primary">القسم الفرعي</label> <span class="text-danger">*</span>

								<select class="form-control" name="system_ads_pages[sub_id]" disabled  id="sec_section">

								</select>
							</div>
							<div class="from-group col-3" style="margin-bottom: 20px;display: none" id="sec_section_div_chack" >
								<label class="text-primary">إظهار الإعلان فى الرئيسية</label>
								<span style="margin-right: 20px">
									<input type="radio" name="system_ads_pages[status]" value="1" class="ml-1"><strong>نعم</strong>
									<input type="radio" name="system_ads_pages[status]" value="0" class="ml-1"><strong>لا</strong>
								</span>


							</div>
						</div>
						@endif
	            		<div class="col-sm-2" style="margin-bottom: 20px">
	            			
							<div class="from-group" style="margin-top: 10px">
								<label class="text-primary">  النوع</label> <span class="text-danger">*</span>
								<input type="text"  readonly class="form-control"  value="{{$ads->type}}" placeholder=" النوع">
							</div>
							<div class="from-group" style="margin-top: 10px">
								<label class="text-primary">  الاعضاء</label> <span class="text-danger">*</span>
								<input type="text"  readonly class="form-control" value="{{$ads->AdsUser->name}}" placeholder=" الاعضاء">
							</div>
							{{-- company --}}
							<div class="from-group" style="margin-top: 10px">
								<div class="from-group">
									<label class="text-primary">  الشركة : <span class="text-primary">*</span></label>
									<input type="text" readonly  readonly class="form-control" value="{{$ads->Company->name}}" placeholder=" الشركة">
								</div>
							</div>
							<div class="custom-control custom-radio" style="margin-top: 10px">
								<input type="radio" id="customRadio1" name="status" value="1" @if($ads->status == '1') checked @endif class="custom-control-input">
								<label class="custom-control-label" for="customRadio1">لائق</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" id="customRadio2" name="status" value="2" @if($ads->status == '2') checked @endif class="custom-control-input">
								<label class="custom-control-label" for="customRadio2"> غير لائق</label>
							</div>
	
	            		</div>

	            		{{-- details --}}
	            		<div class="col-sm-10">
	            			<div class="row">


								{{-- title --}}
								<div class="col-sm-4" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> العنوان : <span class="text-primary">*</span></label>
										<input type="text"  name="title" class="form-control" {{$ads->type == 'notification' ? 'required' : ''}} {{$ads->type == 'notification' && $ads->status == 0? '' : 'readonly'}} value="{{$ads->title}}" placeholder=" العنوان">
									</div>
								</div>
								<div class="col-sm-4" style="margin-top: 10px">
									<div class="from-group">
										<label class="text-primary"> تاريخ الانتهاء : <span class="text-primary">*</span></label>
										<input type="date"  name="end_date" class="form-control"  {{$ads->type == 'notification' ? 'required' : ''}} {{$ads->type == 'notification' && $ads->status == 0 ? '' : 'readonly'}} value="{{$ads->end_date}}" placeholder=" العنوان">
									</div>
								</div>

								@if($ads->type == 'notification')
									<div class="col-sm-4" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary"> الوقت : <span class="text-primary">*</span></label>
											<input type="time"  name="not_time" {{$ads->type == 'notification' ? 'required' : ''}} {{$ads->status == 0 ? '' : 'readonly'}} class="form-control" value="{{$ads->not_time}}" placeholder=" الوقت">
										</div>
									</div>
								@else
									{{-- link --}}
									<div class="col-sm-4" style="margin-top: 10px">
										<div class="from-group">
											<label class="text-primary"> الرابط : <span class="text-primary">*</span></label>
											<input type="text"  name="link" class="form-control" value="{{$ads->link}}" placeholder=" الرابط">
										</div>
									</div>
								@endif
								<div class="col-sm-12 marbo" style="margin-top: 10px">
									<label class="text-primary"> محتوي الاعلان</label> <span class="text-primary">*</span>
									<textarea class="form-control" {{$ads->type == 'notification' ? 'required' : ''}} {{$ads->type == 'notification' && $ads->status == 0 ? '' : 'readonly'}}  rows="5" name="desc" class="form-control" placeholder=" محتوي الاعلان ">{{$ads->desc}}</textarea>
									
								</div>
	            				
	            			</div>
	            		</div>
						{{-- submit --}}
	            			<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
	            	</div>
            	</form>
            </div>
          </div>
        </div>
		{{--warning--}}
		<div class="modal fade" id="modal-secondary">
		<div class="modal-dialog">
		<div class="modal-content bg-secondary">
			<div class="modal-body">
			<p>هذه الصفحة خاصة   بإضافة عضو</p>
			</div>
		</div>
		</div>
		</div>
    </div>
@endsection

@section('script')
<script type="text/javascript">
	function ChooseAvatar(){$("input[name='avatar']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};
</script>
<script>
	$( window ).on( "load", function() {
		$('#type_place').change();
	});
</script>
	<script>

		var section = '{{ isset($ads->SystemAdsPages) && count($ads->SystemAdsPages) > 0 ? $ads->SystemAdsPages[0]->section_type : ''}}';
		var main = '{{ $ads->main == 1 ? '1' : '0'}}';
		var sub_section = '{{ isset($ads->SystemAdsPages) && count($ads->SystemAdsPages) > 0 ? $ads->SystemAdsPages[0]->sub_id : '' }}';

		var type_palce;
		if(main == 1){
			$('#sec_section_div').hide();
		}
		$('#type_place').change(function (){
			type_palce = this.value;
			if(type_palce == 'home'){
				$('#section_div').empty().hide();
				$('#sec_section_div_chack').hide();
			}else {
				$('#section_div').show();
			}
			$.ajax({
				url     : "{{ url('/systemads/getSection/') }}"+'/'+type_palce,
				method  : 'GET',
				success : function(data,result){
					$('#section').empty();
					$.each(data,function(k,v){
						if(section == v.type){
							$('#section').append(`<option value="${v.type}" selected>${v.name}</option>`);
						}else{
							$('#section').append(`<option value="${v.type}">${v.name}</option>`);
						}
					})
				}}).done(

					$.ajax({
						url     : "{{ url('/systemads/getSubSection/') }}"+'/'+type_palce+'/'+section+'/'+main,
						method  : 'GET',
						success : function(data,result){

							$('#sec_section').empty();
							if(typeof(data.sub_sections) != "undefined"){
								$.each(data.sub_sections,function(k,v){

									if(sub_section == v.id){
										$('#sec_section').append(`<option value="${v.id}" selected>${v.name}</option>`);
									}else{
										$('#sec_section').append(`<option value="${v.id}">${v.name}</option>`);
									}

								})
							}
							if(typeof(data.chack) != "undefined"){
								$('#sec_section_div').hide();
								$('#sec_section_div_chack').show();
							}

						}})
			);

		})


		$('#section').change(function (){
			section = this.value;
			$.ajax({
				url     : "{{ url('/systemads/getSubSection/') }}"+'/'+type_palce+'/'+section+'/'+main,
				method  : 'GET',
				success : function(data,result){
					$('#sec_section').empty();
					$.each(data.sub_sections,function(k,v){
						$('#sec_section').append(`<option value="${v.id}">${v.name}</option>`);
					})
				}})
		})

	</script>


<script type="text/javascript">
	function ChooseAvatar1(){$("input[name='images[]']").click()}
	function ChooseAvatar(){$("input[name='image']").click()}
	var loadAvatar = function(event) {
		var output = document.getElementById('avatar');
		output.src = URL.createObjectURL(event.target.files[0]);
	};

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


