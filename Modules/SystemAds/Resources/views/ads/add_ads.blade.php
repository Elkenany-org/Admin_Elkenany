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
              <h5 class="m-0" style="display: inline;">إضافة اعلان جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <div class="card-body">
            	<form action="{{route('Storesystemads')}}" method="post" enctype="multipart/form-data">
	            	<div class="row">
            			{{csrf_field()}}
	            		{{-- avatar --}}
	            		<div class="col-sm-2" style="margin-bottom: 20px">
	            			<div class="from-group ">
	            				<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label>
	            				<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
	            				<img src="{{asset('dist/img/placeholder2.png')}}" onclick="ChooseAvatar()" id="avatar">
	            			</div>
							<div class="from-group" style="margin-top: 10px">
							<label class="text-primary">  النوع</label> <span class="text-danger">*</span>
                              <select name="type" class="form-control">
							  		<option value="" selected disabled>اختار</option>
                                    <option value="banner">banner</option>
									<option value="logo">logo</option>
									<option value="sort">sort</option>
									<option value="popup">popup</option>
									<option value="notification">notification</option>
                              </select>
							</div>
							<div class="from-group" style="margin-top: 10px">
							<label class="text-primary">  الاعضاء</label> <span class="text-danger">*</span>
                              <select name="ads_user_id" class="form-control">
							  	<option value="" selected disabled>اختار</option>
                                @foreach($users as $value)
                                    <option value="{{$value->id}}">{{$value->name}}</option>
                                @endforeach
                              </select>
							</div>
	            		</div>

	            		{{-- details --}}
	            		<div class="col-sm-10">
	            			<div class="row">
	            				{{-- title --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary"> العنوان : <span class="text-primary">*</span></label>
	            						<input type="text" name="title" class="form-control" value="{{old('title')}}" placeholder=" العنوان">
	            					</div>
	            				</div>

	            				{{-- company_id --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary">  الشركة : <span class="text-primary">*</span></label>
	            						<input type="number" name="company_id" class="form-control" value="{{old('company_id')}}" placeholder=" الشركة">
	            					</div>
	            				</div>


	            				{{-- link --}}
	            				<div class="col-sm-6" style="margin-top: 10px">
	            					<div class="from-group">
	            						<label class="text-primary"> الرابط : <span class="text-primary">*</span></label>
	            						<input type="text" name="link" class="form-control" value="{{old('link')}}" placeholder=" الرابط">
	            					</div>
	            				</div>

								<div class="col-sm-12 marbo" style="margin-top: 10px">
									<label class="text-primary"> محتوي الاعلان</label> <span class="text-primary">*</span>
									<textarea class="form-control" rows="5" name="desc" class="form-control" placeholder=" محتوي الاعلان "></textarea>
									
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
@endsection


