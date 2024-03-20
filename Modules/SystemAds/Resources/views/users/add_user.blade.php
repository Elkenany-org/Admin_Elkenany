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
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">إضافة عضو جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('storeuserads')}}" method="post" enctype="multipart/form-data">
	            	<div class="row">
            			{{csrf_field()}}
	            	

	            		{{-- details --}}
	            		<div class="col-sm-12">
	            			<div class="row">
	            				{{-- name --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary">إسم المستخدم : <span class="text-danger">*</span></label>
	            						<input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="إسم المستخدم " required="">
	            					</div>
	            				</div>

	            				{{-- email --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">البريد الإلكتروني : <span class="text-danger">*</span></label>
	            						<input type="email" name="email" class="form-control" value="{{old('email')}}" placeholder="البريد الإلكتروني" required="">
	            					</div>
	            				</div>

	            				{{-- password --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	                				<div class="from-group">
	            						<label class="text-primary">كلمة المرور : <span class="text-danger">*</span></label>
	            						<input type="text" name="password" class="form-control" value="{{old('password')}}" placeholder="كلمة المرور" required="">
	            					</div>
	            				</div>

	            				{{-- phone --}}
	            				<div class="col-sm-6">
	            					<div class="from-group" style="margin-top: 10px">
	            						<label class="text-primary">رقم الهاتف : <span class="text-danger">*</span></label>
	            						<input type="text" name="phone" class="form-control" value="{{old('phone')}}" placeholder="رقم الهاتف " required="">
	            					</div>
	            				</div>

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
						{{-- submit --}}
	            			<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
	            	</div>
            	</form>
            </div>
          </div>
        </div>
		{{csrf_field()}}
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
	if ($('input[name="company_id[]"]').is(':checked')) { 
        $('input[name="company_id[]"]').not(':checked').parent().fadeOut().remove();
			$.each(s,function(k,v){
				if ($('input[name="company_id[]"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="checkbox" value="${v.id}"  class="company_id" name="company_id[]">
							<label class="text-info">${v.name}</label>
						</div>
					`);
				}
		})
	}else{
		$('.company_search_result').html('')
		$.each(s,function(k,v){
				if ($('input[name="company_id[]"]:checked').val() != v.id) { 
					$('.company_search_result').append(`
						<div class="col-sm-3 result_style">
							<input type="checkbox" value="${v.id}"  class="company_id" name="company_id[]">
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


