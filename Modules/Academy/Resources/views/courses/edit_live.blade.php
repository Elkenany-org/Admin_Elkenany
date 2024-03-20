@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100%;
		height:250px;
	}
	#avatar:hover{
		width: 100%;
		height:250px;
		cursor: pointer;
	}
</style>
@endsection
@section('content')
<div class="container-fluid">
	<div class="card card-primary card-outline">
		<div class="card-header">
			<h5 class="m-0" style="display: inline;"> تعديل اللايف</h5>
		</div>
		<div class="card-body">
			<form action="{{route('updatelive')}}" method="post" enctype="multipart/form-data">
				{{csrf_field()}}
				{{-- online --}}
				<input type="hidden" name="lid" value="{{$live->id}}">
				<div class="row">
				<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> العنوان: <span class="text-primary">*</span></label>
							<input type="text" name="edit_title_live" class="form-control" value="{{$live->title}}" placeholder=" العنوان">
						</div>
						</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> التاريخ: <span class="text-primary">*</span></label>
							<input type="date" name="edit_date_live" class="form-control" value="{{$live->date}}" placeholder=" التاريخ">
						</div>
					</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> الوقت: <span class="text-primary">*</span></label>
							<input type="time" name="edit_time_live" class="form-control" value="{{$live->time}}" placeholder=" الوقت">
						</div>
					</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> الرابط: <span class="text-primary">*</span></label>
							<input type="text" name="edit_link_live" class="form-control" value="{{$live->link}}" placeholder=" الرابط">
						</div>
					</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> اسم التطبيق: <span class="text-primary">*</span></label>
							<input type="text" name="edit_application" class="form-control" value="{{$live->application}}" placeholder=" التطبيق">
						</div>
					</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> المحاضر: <span class="text-primary">*</span></label>
							<input type="text" name="edit_prof_live" class="form-control" value="{{$live->prof}}" placeholder=" المحاضر">
						</div>
						</div>
					<div class="col-sm-4" style="margin-top: 10px">
						<div class="from-group">
							<label class="text-primary"> عدد الساعات: <span class="text-primary">*</span></label>
							<input type="text" name="edit_hourse_count_live" class="form-control" value="{{$live->hourse_count}}" placeholder=" عدد الساعات">
						</div>
					</div>
				</div>
				{{-- submit --}}
				<button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('script')


<script type="text/javascript">
</script>


@endsection


