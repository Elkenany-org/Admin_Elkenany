@extends('layouts.app')

@section('style')
<style type="text/css">

/*#regForm {
    background-color: #ffffff;
    margin: 100px auto;
    padding: 40px;
    width: 70%;
    min-width: 300px;
  }*/
  
  /* Style the input fields */
  input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    font-family: Raleway;
    border: 1px solid #aaaaaa;
  }
  
  /* Mark input boxes that gets an error on validation: */
  input.invalid {
    background-color: #ffdddd;
  }
  
  /* Hide all steps by default: */
  .tab {
    display: none;
  }
  
  /* Make circles that indicate the steps of the form: */
  .step {
    height: 15px;
    width: 15px;
    margin: 0 2px;
    background-color: #bbbbbb;
    border: none;
    border-radius: 50%;
    display: inline-block;
    opacity: 0.5;
  }
  
  /* Mark the active step: */
  .step.active {
    opacity: 1;
  }
  
  /* Mark the steps that are finished and valid: */
  .step.finish {
    background-color: #4CAF50;
  }

  .m_top{
      margin-top:10px
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
    .result_style{
		border: 1px solid #12a3b8;
		padding-top: 12px;
		border-radius: 22px;
	}
</style>
@endsection

@section('content')
<div class="card card-primary card-outline">
    <div class="card-header">
        <h5 class="m-0" style="display: inline;" style="float: right">إضافة مقر جديدة <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5> 
    </div>
    <div class="card-body" style="padding: 0">
        <form id="regForm" action="{{route('storeoffices')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            
        
            <!-- tabs -->
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                
                    {{--  offices --}}
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-add-tab" data-toggle="pill" href="#custom-content-below-add" role="tab" aria-controls="custom-content-below-add" aria-selected="false">  بيانات المقر </a>
                    </li>

                    {{-- contect --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-contect-tab" data-toggle="pill" href="#custom-content-below-contect" role="tab" aria-controls="custom-content-below-contect" aria-selected="false">  التواصل</a>
                    </li>



                </ul>
            </div>

            <div class="tab-content" id="custom-content-below-tabContent">

                {{--  offices --}}
                <div class="tab-pane fade show active" style="padding-bottom: 50px;" id="custom-content-below-add" role="tabpanel" aria-labelledby="custom-content-below-add-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6" style="margin-top: 10px">
                                <label class="text-primary">إسم المقر <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{old('name')}}" name="name" placeholder=" الاسم" required>
                                <label class="text-primary">نبزة مختصرة <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="desc" placeholder="نبزة مختصرة " required>{{old('desc')}}</textarea>
                            </div>

                          
                            <div class="col-sm-6 marbo" style="margin-top: 10px">
                                <label  class="text-primary">العنوان</label>
                                <input type="text" id="pac-input" class="form-control" placeholder="  " name="address">
                                <label for="exampleInputEmail1">lat</label>
                                <input type="text" class="form-control"  placeholder=" lat" value="" id="latitude" required name="latitude">
                                <label for="exampleInputEmail1">long</label>
                                <input type="text" class="form-control"  placeholder=" long" value="" id="longitude"  required name="longitude">
                            </div>
                            
                        </div>
                    </div>
                </div>
                
                {{-- contect --}}
                <div class="tab-pane fade" style="padding-bottom: 30px;" id="custom-content-below-contect" role="tabpanel" aria-labelledby="custom-content-below-contect-tab">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> الموبايل <span class="text-danger">*</span></label>
                                <div class="row mobiles" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="text" name="mobiles[]" class="form-control" placeholder="الموبايل" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_mobiles">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> الهاتف الارضي <span class="text-danger">*</span></label>
                                <div class="row phones" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="text" name="phones[]" class="form-control" placeholder="الهاتف الارضي" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_phones">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> الفاكس <span class="text-danger">*</span></label>
                                <div class="row faxs" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="text" name="faxs[]" class="form-control" placeholder="الفاكس" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_faxs">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> البريد <span class="text-danger">*</span></label>
                                <div class="row emails" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="text" name="emails[]" class="form-control" placeholder="البريد" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_emails">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

               



            </div>
            
            {{-- submit --}}
            <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; margin-bottom:30px; " class="btn btn-outline-primary btn-block">حفظ</button>
        </form>       
    </div>
    {{--warning--}}
    <div class="modal fade" id="modal-secondary">
    <div class="modal-dialog">
    <div class="modal-content bg-secondary">
        <div class="modal-body">
        <p>هذه الصفحة خاصة   باضافة مقر</p>
        </div>
    </div>
    </div>
    </div>
</div>
@endsection

@section('script')

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EGasync defer"></script>
<script type="text/javascript">

</script>

<script type="text/javascript">

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
					<input type="text" name="phones[]" class="form-control" placeholder="الجوال" >
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

$(document).on('click','.add_emails',function(){
	$('.emails').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="emails[]" class="form-control" placeholder=" البريد" >
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

$(document).on('click','.remove_emails',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})

$(document).on('click','.store_food',function(){
	$('.add_real').click();
})

</script>

@endsection


