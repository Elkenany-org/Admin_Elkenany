@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">إرسال إشعار لجميع المستخدمين<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>

            <div class="card-body">
            	<form action="{{route('senfcmnotification')}}" method="post" enctype="multipart/form-data">
	            	<div class="row">
            			{{csrf_field()}}
	            		{{-- avatar --}}
	            		<div class="col-sm-12" style="margin-bottom: 20px">
	            			<div class="from-group ">
	            				<label class="text-primary"> نص الإشعار <span class="text-danger"> * </span></label>
	            				<textarea class="form-control" id="editor" rows="8" name="message" required=""></textarea>
	            			</div>
	            		</div>
        				{{-- submit --}}
        				<div class="col-sm-4 offset-4" style="margin-top: 20px">
        					<button class="btn btn-outline-primary btn-block">إرسال</button>
        				</div>
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
				<p>هذه الصفحة خاصة  بالإشعارات المرسلة للمشرفين</p>
				</div>
			</div>
			</div>
		</div>
    </div>
@endsection

@section('script')
<script>
    CKEDITOR.replace( 'editor' ,{
        contentsLangDirection: 'rtl'
    });
</script>
@endsection
