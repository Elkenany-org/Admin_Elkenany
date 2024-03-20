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
					<h5 class="m-0" style="display: inline;" style="float: right">اضافة  صنف<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="card-body">
					<form role="form" class="contact100-form validate-form" action="{{route('Storestocks')}}" method="POST">
					@csrf
						<div class="row">
							<div class="col-sm-4 comp" style="margin-top: 10px;">
								<label style="margin-top: 10px" class="text-primary"> القسم الرئيسي <span class="text-danger">*</span></label>
								<select name="section_stocks_id" class="form-control section_stocks_id">
									<option value="" disabled selected>إختيار قسم</option>
									@foreach($sections as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>
							
							<div class="col-sm-4 comp" style="margin-top: 10px;">
								<label style="margin-top: 10px" class="text-primary">  الاقسام الفرعية <span class="text-danger">*</span></label>
								<select name="sub_id"  class="form-control sub_id">
								
						
								</select>
							</div>

							<div class="col-sm-4 comp" style="margin-top: 10px;">
								<label style="margin-top: 10px" class="text-primary">  الاصناف <span class="text-danger">*</span></label>
								<select name="fodder_id[]" multiple  class="form-control fodder_id">
								
						
								</select>
							</div>
							
							<div class="col-sm-6 comp" style="margin-top: 10px;">
								<label style="margin-top: 10px" class="text-primary"> القسم الرئيسي للدليل <span class="text-danger">*</span></label>
								<select name="section_id" class="form-control section_id">
									<option value="" disabled selected>إختيار قسم</option>
									@foreach($gsections as $value)
										<option value="{{$value->id}}">{{$value->name}}</option>
									@endforeach
								</select>
							</div>

							<div class="col-sm-6 comp"  style="margin-top: 10px;">
								<label style="margin-top: 10px" class="text-primary">  ابحث عن شركة <span class="text-danger">*</span></label>
								<input type="search" class="form-control company_search" name="company_search">
							</div>
					
							<div class="col-sm-12 " style="margin-top:20px">
								<div class="row company_search_result">
								</div>
							</div>

							<div class="col-sm-4" style="margin-top: 20px;">
								<label>السعر</label> <span class="text-danger">*</span>
								<input type="number" class="form-control" name="price" required>

							</div>

						</div>
						<hr>
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
            <p>هذه الصفحة خاصة باضافة صنف للبرصة الاعلاف</p>
            </div>
          </div>
          </div>
        </div>
 </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
 

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

</script>
@endsection




