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
		<div class="row">
			<div class="col-sm-12">
				<div class="card card-primary card-outline">
					<div class="card-header">
					<h5 class="m-0" style="display: inline;" style="float: right">اضافة منتج او شركة<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="card-body">
					<form role="form" class="contact100-form validate-form" action="{{route('storeMember')}}" method="POST">
					@csrf
					<input type="hidden" name="id" value="{{$section->id}}">
						<div class="row">
							<div class="sel" style="width: 20%; margin-left: auto; margin-right: auto; ">
								<label style="margin-right: 40px; "> اختيار شركة او منتج <span class="text-danger">*</span></label>
								<select name="slect" class="form-control slect">
									<option value="" disabled selected> إختيار النوع</option>
										<option value="0">الشركات</option>
										<option value="1">المنتجات</option>
								</select>
							</div>
						</div>
						
						<div class="row">
							<!-- company -->
							<div class="col-sm-12 comp">

								<div class="row text-center">
									<div class="col-sm-4 comp" style="display: none;margin-top: 10px;margin-right:16%">
										<label style="margin-top: 10px" class="text-primary"> القسم الرئيسي <span class="text-danger">*</span></label>
										<select name="section_id" class="form-control section_id">
											<option value="" disabled selected>إختيار قسم</option>
											@foreach($sections as $value)
												<option value="{{$value->id}}">{{$value->name}}</option>
											@endforeach
										</select>
									</div>

									<div class="col-sm-4 comp"  style="display: none;margin-top: 10px;">
										<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
										<input type="search" class="form-control company_search" name="company_search">
									</div>
								</div>

								<!-- search result -->
								<div class="row">
									<div class="col-sm-12 " style="margin-top:20px">
										<div class="row company_search_result">
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-12 pro" style="display: none;margin-top: 10px;">

								<div class="row text-center">
									<div class="col-sm-4 offset-4 product" >
										<label style="margin-top: 10px" class="text-primary">  ابحث عن منتج <span class="text-danger">*</span></label>
										<input type="search" class="form-control product_search" name="product_search">
									</div>
								</div>

								<!-- search result -->
								<div class="row">
									<div class="col-sm-12 " style="margin-top:20px">
										<div class="row product_search_result">
											@foreach($products as $value)
												<div class="col-sm-3 result_style">
													<input type="radio" value="{{$value->id}}" class="product_id" name="product_id">
													<label class="text-info">{{$value->name}}</label>
												</div>
										@endforeach
										</div>
									</div>
								</div>
							</div>

						</div>
						<hr>
						<div class="row">
							@foreach($section->LocalStockColumns  as $column)
							@if($column->type == null || $column->type == 'price')
							<div class="col-sm-4" style="margin-top: 20px;">
								<label>{{ $column->name }}</label> <span class="text-danger">*</span>
								<input type="text" class="data{{$column->id}}" name="column_id[]" value="{{$column->id}}" hidden>
								<input type="text" name="value[]" class="form-control" required style="margin-bottom: 10px">
							</div>
							@endif
							@if($column->type == 'change' || $column->type == 'state')
							<input type="hidden" class="data{{$column->id}}" name="column_id[]" value="{{$column->id}}" hidden>
							<input type="hidden" name="value[]" value="" class="form-control" required="" style="margin-bottom: 10px">
							@endif
							@endforeach
						</div>
						<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-primary btn-block add_compant_product">حفظ</button>
					</form>
				</div>
			</div>	
		</div>
		{{--warning--}}
        <div class="modal fade" id="modal-secondary">
          <div class="modal-dialog">
          <div class="modal-content bg-secondary">
            <div class="modal-body">
            <p>هذه الصفحة خاصة باضافة عضو للبرصة المحلية</p>
            </div>
          </div>
          </div>
        </div>
 </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
 
$('.update').on('click',function(){
$('.submit').click();
})


$('.addd').on('click',function(){
$('.submit').click();
})

//edit columns !!!!!!!!
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')

	$('.item_name').text(name)
	$("input[name='edit_columns_id']").val(id)
	$("input[name='edit_columns_name']").val(name)
})

{{--  select betwen add product or company  --}}
$(document).on('change','.slect', function(){
	if ( $(this).val() == '0')
      {
		$('.product_id').each(function () {
			$(this).attr('checked',false);
			$(this).prop('checked', false);
		})
		$('.comp').css("display", "block");
		$('.pro').css("display", "none");
      }else
      {
		$('.company_id').each(function () {
			$(this).attr('checked',false);
			$(this).prop('checked', false);
		})
		$('.pro').css("display", "block");
		$('.comp').css("display", "none");
      }
 });


 // get companies
$(document).on('change','.section_id', function(){

	var data = {
	section_id    : $(this).val(),
	_token        : $("input[name='_token']").val()
	}

    $.ajax({
    url     : "{{ url('get-companies') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
		$('.company_search_result').html('')
        $.each(s,function(k,v){
		$('.company_search_result').append(`
			<div class="col-sm-3 result_style">
				<input type="radio" value="${v.id}"  class=" company_id"  name="company_id">
				<label  class="text-info">${v.name}</label>
			</div>
        `);
    })
    }});

});

// search companies
$(document).on('keyup','.company_search', function(){

	var data = {
		search     : $(this).val(),
		section_id : $('.section_id').val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-companies-search') }}",
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

// search product
$(document).on('keyup','.product_search', function(){

	var data = {
		search     : $(this).val(),
		_token     : $("input[name='_token']").val()
	}

	$.ajax({
	url     : "{{ url('get-product-search') }}",
	method  : 'post',
	data    : data,
	success : function(s,result){
		$('.product_search_result').html('')

		$.each(s,function(k,v){
		$('.product_search_result').append(`
			<div class="col-sm-3 result_style">
				<input type="radio" value="${v.id}"  class="product_id" name="product_id">
				<label  class="text-info">${v.name}</label>
			</div>
		`);
	})
	}});

});


// add company or product
$(document).on('click','.add_compant_product',function(e){
	var company = $('.company_id').is(":checked")
	var product = $('.product_id').is(":checked")
	if(company && product)
	{
		e.preventDefault()
		//toastr.info('يجب إختيار شركة او مُنتج ! ')
		alert('يجب إختيار شركة او مُنتج ! ')
	}else if(!company && !product)
	{
		e.preventDefault()
		alert('يجب إختيار منتج او شركة فقط ! ')
	}
})
   
</script>
@endsection


