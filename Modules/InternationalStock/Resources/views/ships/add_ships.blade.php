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
              <h5 class="m-0" style="display: inline;">إضافة حركة سفن<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('Storeships')}}" method="post" enctype="multipart/form-data">
					{{csrf_field()}}
					<div class="row">
						

								{{-- name --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> اسم السفينة: <span class="text-danger">*</span></label>
										<input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder=" اسم السفينة" required="">
									</div>
								</div>
								{{-- load --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary"> حمولة السفينة: <span class="text-danger">*</span></label>
										<input type="number" name="load" class="form-control" value="{{old('load')}}" placeholder=" حمولة السفينة" required="">
									</div>
								</div>
								{{-- country --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  المنشأ: <span class="text-danger">*</span></label>
										<input type="text" name="country" class="form-control" value="{{old('country')}}" placeholder="  المنشأ" required="">
									</div>
								</div>
								{{-- date --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  التاريخ: <span class="text-danger">*</span></label>
										<input type="date" name="date" class="form-control" value="{{old('date')}}" placeholder="  التاريخ" required="">
									</div>
								</div>
								{{-- agent --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  الوكيل الملاحي: <span class="text-danger">*</span></label>
										<input type="text" name="agent" class="form-control" value="{{old('agent')}}" placeholder="   الوكيل الملاحي" required="">
									</div>
								</div>
								{{-- dir_date --}}
								<div class="col-sm-3"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  تاريخ التوجيه: <span class="text-danger">*</span></label>
										<input type="date" name="dir_date" class="form-control" value="{{old('dir_date')}}" placeholder="    تاريخ التوجيه" required="">
									</div>
								</div>
								{{-- ports --}}
								<div class="col-sm-4"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  المواني <span class="text-danger">*</span></label>
										<select name="port_id" class="form-control" required>
										<option value="" disabled selected>إختيار ميناء</option>
										@foreach($ports as $value)
											<option value="{{$value->id}}">{{$value->name}}</option>
										@endforeach
										</select>
									</div>
								</div>
							
								{{-- products --}}
								<div class="col-sm-4"style="margin-top: 20px;">
									<div class="from-group">
										<label class="text-primary">  المنتجات <span class="text-danger">*</span></label>
										<select name="product_id" class="form-control" required>
										<option value="" disabled selected>إختيار منتج</option>
										@foreach($products as $value)
											<option value="{{$value->id}}">{{$value->name}}</option>
										@endforeach
										</select>
									</div>
								</div>

								<div class="col-sm-4 comp"  style="margin-top: 10px;">
									<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
									<input type="search" class="form-control company_search" name="company_search">
								</div>

								<div class="col-sm-12 " style="margin-top:20px">
									<div class="row company_search_result">
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
				<p>هذه الصفحة خاصة    باضافة حركة سفن</p>
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

// search companies
$(document).on('keyup','.company_search', function(){

var data = {
	search     : $(this).val(),
	_token     : $("input[name='_token']").val()
}

$.ajax({
url     : "{{ url('get-companies-search-ship') }}",
method  : 'post',
data    : data,
success : function(s,result){
	$('.company_search_result').html('')

	$.each(s,function(k,v){
	$('.company_search_result').append(`
		<div class="col-sm-3 result_style">
			<input type="radio" value="${v.id}"  class="company_id" name="company_id">
			<label class="text-info">${v.name}</label>
		</div>
	`);
})
}});

});


</script>
@endsection


