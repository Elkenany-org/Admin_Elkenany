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
					<a class="nav-link active" id="custom-content-below-offices-tab" data-toggle="pill" href="#custom-content-below-offices" role="tab" aria-controls="custom-content-below-offices" aria-selected="true">بيانات الشركة</a>
				</li>

				{{-- cont --}}
				<li class="nav-item">
					<a class="nav-link" id="custom-content-below-cont-tab" data-toggle="pill" href="#custom-content-below-cont" role="tab" aria-controls="custom-content-below-cont" aria-selected="false">التواصل </a>
				</li>


			</ul>
		</div>
		<div class="tab-content" id="custom-content-below-tabContent">

			{{-- edit --}}
			<div class="tab-pane fade show active" id="custom-content-below-offices" role="tabpanel" aria-labelledby="custom-content-below-offices-tab">	
				<div class="row">
					<div class="col-sm-12">
							<div class="card-body">
								<form action="{{route('updateoffices')}}" method="post" enctype="multipart/form-data">
									<div class="row">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$offices->id}}">

										<div class="col-sm-12 marbo">
											<div class="row">
													<div class="col-sm-6">
														<label>الاسم <span class="text-danger">*</span></label>
														<input type="text" value="{{$offices->name}}" class="form-control" name="name" placeholder=" الاسم" required></input>
													
														<div class="custom-control custom-radio" style="margin-top: 10px">
															<input type="radio" id="customRadio1" name="status" value="1" @if($offices->status == '1') checked @endif class="custom-control-input">
															<label class="custom-control-label" for="customRadio1">مقر غير رئيسي</label>
														</div>
														<div class="custom-control custom-radio">
															<input type="radio" id="customRadio2" name="status" value="0" @if($offices->status == '0') checked @endif class="custom-control-input">
															<label class="custom-control-label" for="customRadio2">  مقر رئيسي</label>
														</div>
													</div>
												
												<div class="col-sm-6 marbo">
													<label for="exampleInputEmail1">العنوان</label>

													<input type="text" id="pac-input" class="form-control" placeholder="  " required value="{{ $offices->address }}" name="address">
													<label for="exampleInputEmail1">lat</label>
													<input type="text" class="form-control"  placeholder=" lat" value="{{$offices->latitude}}" id="latitude" required name="latitude">
													<label for="exampleInputEmail1">long</label>
													<input type="text" class="form-control"  placeholder=" long" value="{{$offices->longitude}}" id="longitude"  required name="longitude">
												</div>
												<div class="col-sm-6 m_top" style="margin-top: 10px">
													<label class="text-primary">نبزة مختصرة <span class="text-danger">*</span></label>
													<textarea class="form-control" rows="5" name="desc" placeholder="نبزة مختصرة " required>{{$offices->desc}}</textarea>
												</div> 
											</div>
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
				<form action="{{route('Updatecontactoffice')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<input type="hidden" name="id" value="{{$offices->id}}">
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


		</div>
	</div>
</div>
@endsection

@section('script')
<script type="text/javascript">

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

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EG
 async defer"></script>


 
@endsection


