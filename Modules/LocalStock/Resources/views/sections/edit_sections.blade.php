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

	<div class="card-body">
	<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
		{{-- edit section --}}
		<li class="nav-item">
			<a class="nav-link active" id="custom-content-below-section-tab" data-toggle="pill" href="#custom-content-below-section" role="tab" aria-controls="custom-content-below-section" aria-selected="true">معلومات عن القسم </a>
		</li>
		
		{{-- columns --}}
		<li class="nav-item">
			<a class="nav-link" id="custom-content-below-columns-tab" data-toggle="pill" href="#custom-content-below-columns" role="tab" aria-controls="custom-content-below-columns" aria-selected="false">عناوين اضافية</a>
		</li>
	</ul>
	</div>
	<div class="tab-content" id="custom-content-below-tabContent">
		<div class="tab-pane fade show active"  id="custom-content-below-section" role="tabpanel" aria-labelledby="custom-content-below-section-tab">
			<div class="row">
				<div class="col-sm-12">
						<div class="card-header">
							<h6 class="m-0" style="display: inline;">تعديل قسم<span class="text-primary"> {{$section->name}} </span></h6>
						</div>
						<div class="row">
							<div class="col-sm-12">
									<div class="card-body">
										<form action="{{route('updatelocalsectionss')}}" method="post" enctype="multipart/form-data">
											{{csrf_field()}}
											<div class="row">
												<div class="col-sm-6">
													<input type="hidden" name="edit_id" value="{{$section->id}}">
													<label>إسم القسم</label> <span class="text-danger">*</span>
													<input type="text" value="{{$section->name}}" name="edit_name" class="form-control" required="" style="margin-bottom: 10px">
												</div>
										
												<div class="col-sm-6 marbo">
													<label >إختيار صورة <span class="text-primary"> * </span></label><br>
													<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
													<img src="{{asset('uploads/sections/sub/'.$section->image)}}" style="width: 100%;height:200px" onclick="ChooseAvatar()" id="avatar">
												</div>
												
												<div class="col-sm-12">
													<label> ملاحظات</label> <span class="text-primary">*</span>
													<textarea class="form-control" rows="8" name="edit_note" class="form-control" placeholder=" ملاحظات "  style="margin-bottom: 10px">{{$section->note}}</textarea>
												</div>
												@foreach($main_sections as $value)
													@if(in_array($value->id, $secs))
														<div class="col-sm-2 result_style">
															
																<input  style="display: inline;width: 50%;" type="checkbox" checked name="sections[]" value="{{ $value->id }}">
																<label class="text-info">{{ $value->name }}</label>
															
														</div>
													@else
													<div class="col-sm-2 result_style">
															
															<input  style="display: inline;width: 50%;" type="checkbox" name="sections[]" value="{{ $value->id }}">
															<label class="text-info">{{ $value->name }}</label>
														
													</div>
													@endif
												@endforeach
												<div class="col-sm-12">
													<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto;" type="submit" class="btn btn-outline-primary btn-block">تحديث</button>
												</div>
											</div>
										</form>
									</div>
							</div>
						</div>
					
				</div>
			</div>
		</div>

		{{-- columns --}}
		<div class="tab-pane fade"  id="custom-content-below-columns" role="tabpanel" aria-labelledby="custom-content-below-columns-tab">
			<div class="row">
				<div class="col-sm-12">
					<div class="card-header">
						<h5 class="m-0" style="display: inline;" style="float: right">العناوين الاضافية</h5>
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
							إضافة عمود 
							<i class="fas fa-plus"></i>
						</button>
					</div>
					<div class="card-body">
						<table id="example1" class="table table-bordered table-hover table-striped">
							<thead>
							<tr>
							<th>#</th>
							<th>الاسم</th>
							<th>التاريخ</th>
							<th>التحكم</th>
							</tr>
							</thead>
							<tbody>
							@foreach($section->LocalStockColumns as $key => $value)
							@if($value->type == null)
							<tr>
								<td>{{$key+1}}</td>
								<td>{{$value->name}}</td>
								<td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
								<td>
								<a href="" 
								class="btn btn-info btn-sm edit"
								data-toggle="modal"
								data-target="#modal_edit"
								data-id    = "{{$value->id}}"
								data-name  = "{{$value->name}}"
								>  تعديل <i class="fas fa-edit"></i></a>
								
								<form action="{{route('Deletecolumn')}}" method="post" style="display: inline-block;">
										{{csrf_field()}}
										<input type="hidden" name="id" value="{{$value->id}}">
										<button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
								</form>
								</td>
							</tr>
							@endif
							@endforeach
							</tfoot>
						</table>
					</div>
				</div>
			</div>
			{{-- edit columns modal --}}
			<div class="modal fade" id="modal_edit">
				<div class="modal-dialog">
					<div class="modal-content bg-primary">
						<div class="modal-header">
						<h4 class="modal-title"> تعديل </h4>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<form action="{{route('updatecolumn')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="edit_columns_id" value="">
									<label>إسم المنتج</label> <span class="text-danger">*</span>
									<input type="text" name="edit_columns_name" class="form-control" placeholder="إسم المنتج " required="" style="margin-bottom: 10px">
									<button type="submit" class="submit" id="submit" style="display: none;"></button>
							</form>
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="btn btn-outline-light update">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
						</div>
					</div>
				</div>
			</div>

			{{-- add column modal --}}
			<div class="modal fade" id="modal-primary">
				<div class="modal-dialog">
					<div class="modal-content bg-primary">
						<div class="modal-header">
							<h4 class="modal-title">إضافة عمود جديد</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span></button>
						</div>
						<div class="modal-body">
							<form action="{{route('columnStore')}}" method="post" enctype="multipart/form-data">
									{{csrf_field()}}
									<input type="hidden" name="id" value="{{$section->id}}">
									<label>إسم العمود</label> <span class="text-danger">*</span>
									<input type="text" name="name" class="form-control" placeholder="إسم العمود " required="" style="margin-bottom: 10px">
									<button type="submit" class="submit worring" id="submit" style="display: none;"></button>
							</form>
						</div>
						<div class="modal-footer justify-content-between">
							<button type="button" class="addd save btn btn-outline-light ">حفظ</button>
							<button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
						</div>
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
 
//edit image
function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

 $('.update').on('click',function(){
        $('.submit').click();
    })


$('.addd').on('click',function(){
	$('.submit').click();
})

//edit columns
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var name       = $(this).data('name')

	

	
	$('.item_name').text(name)
	$("input[name='edit_columns_id']").val(id)
	$("input[name='edit_columns_name']").val(name)
	
	
})
	
	
$('.worring').on('click',function(e){
	var result = confirm('هذه العملية ستستغرق وقت طويل هل تريد الاستمرار ؟ ')
	if(result == false)
	{
		e.preventDefault()
	}
})

   
</script>
@endsection


