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
        <h5 class="m-0" style="display: inline;" style="float: right">إضافة معرض جديد <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5> 
    </div>
    <div class="card-body" style="padding: 0">
        <form id="regForm" action="{{route('storeshow')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
        
            <!-- tabs -->
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                
                    {{--  show --}}
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-add-tab" data-toggle="pill" href="#custom-content-below-add" role="tab" aria-controls="custom-content-below-add" aria-selected="false">  بيانات المعرض </a>
                    </li>


                    {{-- images --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-images-tab" data-toggle="pill" href="#custom-content-below-images" role="tab" aria-controls="custom-content-below-images" aria-selected="false">  اضافة صور</a>
                    </li>

                    {{-- cost --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-cost-tab" data-toggle="pill" href="#custom-content-below-cost" role="tab" aria-controls="custom-content-below-cost" aria-selected="false">    تكلفة الدخول</a>
                    </li>


                </ul>
            </div>

            <div class="tab-content" id="custom-content-below-tabContent">

                {{--  show --}}
                <div class="tab-pane fade show active" style="padding-bottom: 50px;" id="custom-content-below-add" role="tabpanel" aria-labelledby="custom-content-below-add-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-3" style="margin-top: 10px">
                                <label class="text-primary">إسم المعرض <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{old('name')}}" name="name" placeholder=" الاسم" required>
                            </div>
                            <div class="col-sm-3" style="margin-top: 10px">
                                <label class="text-primary">  الدول <span class="text-danger">*</span></label>
                                <select name="country_id" class="form-control country" required>
                                    <option value="" disabled selected>إختيار دولة</option>
                                    @foreach($countries as $value)
                                        <option value="{{$value->id}}">{{$value->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3" style="margin-top: 10px">
                                <label class="text-primary"> المحافظة <span class="text-danger">*</span></label>
                                <select name="city_id" class="form-control cities" required>

                                </select>
                            </div>

                            
                         
                          
                            <div class="col-sm-3" style="margin-top: 10px">
                                <label class="text-primary">  النوع <span class="text-danger">*</span></label>
                                <select name="paied" class="form-control" required>
                                    <option value="" disabled selected>إختيار </option>
                                    <option value="0">مجاني</option>
                                    <option value="1">مدفوع</option>
                                </select>
                            </div>

                            {{-- sections --}}
                            <div class="col-sm-12">
                                <label class="text-primary">الاقسام الرئيسية</label> <span class="text-danger">*</span>
                                <div class="row">
                                    @foreach($sections as $value)
                                        <div class="col-sm-2 result_style">
                                            <input style="display: inline;width: 50%;" type="checkbox" class="major" name="Section[]" value="{{ $value->id }}">
                                            <label class="text-info">{{ $value->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> المواعيد <span class="text-danger">*</span></label>
                                <div class="row times" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="date" name="times[]" class="form-control" placeholder="المواعيد" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_times">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4 marbo"  style="margin-top: 10px">
                                <label  class="text-primary"> الوقت <span class="text-danger">*</span></label>
                                <div class="row watch" >
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8" style="padding-left: 5px ">
                                                <input type="text" name="watch[]" class="form-control" placeholder="الوقت" >
                                            </div>
                                            <div class="col-sm-1" style="padding: 0 ">
                                                <button type="button" class="btn btn-primary btn-block add_watch">
                                                    <i style="margin: 0px -7px " class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- sections --}}
                            <div class="col-sm-12">
                                <label class="text-primary"> الجهات المنظمة</label> <span class="text-danger">*</span>
                                <div class="row">
                                    @foreach($organisers as $value)
                                        <div class="col-sm-2 result_style ">
                                            <input style="display: inline;width: 50%;" type="checkbox" name="organs[]" value="{{ $value->id }}">
                                            <label class="text-info">{{ $value->name }}</label>
                                                    
                                        </div>
                                  
                                    @endforeach
                                </div>
                            </div>
                          
                            <div class="col-sm-6 m_top" style="margin-top: 10px">
                                <label class="text-primary">نبزة مختصرة <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="8" name="desc" placeholder="نبزة مختصرة " required>{{old('short_desc')}}</textarea>
                            </div>  
                            <div class="col-sm-6 marbo" style="margin-top: 10px">
                                <label class="text-primary" >إختيار صورة <span class="text-danger"> * </span></label><br>
                                <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                                <img src="{{asset('dist/img/placeholder2.png')}}" style="width: 100%;height:200px" onclick="ChooseAvatar()" id="avatar">
                            </div>
                      
                        </div>
                       
                    </div>
                </div>
                
                {{-- images --}}
                <div class="tab-pane fade"  id="custom-content-below-images" role="tabpanel" aria-labelledby="custom-content-below-images-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card  card-outline">
                                    <div class="card-header">
                                        <h5 class="m-0" style="display: inline;" style="float: right">اضافة صور</h5>
                                        <div class="btn btn-primary" style="float: left;height:36px;">
                                        <input type="file" name="images[]" id="gallery1"  style="display: none;" accept="image/*" multiple>
                                        <label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 marbo img">
                                                <div class="gallery">
                                            
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               



                {{-- cost --}}
                <div class="tab-pane fade" style="padding-top: 20px" id="custom-content-below-cost" role="tabpanel" aria-labelledby="custom-content-below-cost-tab">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-12 marbo"  style="margin-top: 20px">
                                <label>   تكلفة الدخول  <span class="text-danger">*</span></label>
                                <div class="row preparation">

                
                                    <div class="col-sm-5" style="padding: 0 15px 0 3px">
                                        <input type="text" name="kind[]" class="form-control" placeholder=" النوع" >
                                    </div>
                                    
                                    <div class="col-sm-5" style="padding: 0 15px 0 3px">
                                        <input type="text" name="price[]" class="form-control" placeholder=" السعر" >
                                    </div>
                                    
                                

                                    {{--  <div class="col-sm-1" style="padding: 0 0 0 1px">
                                        <button type="button" class="btn btn-danger btn-block" disabled>
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </div>  --}}
                                    <div class="col-sm-1" style="padding: 0 2px 0px 0px;">
                                        <button type="button" class="btn btn-primary btn-block add_preparation">
                                            <i class="fas fa-plus"></i>
                                        </button>
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
        <p>هذه الصفحة خاصة   باضافة معرض</p>
        </div>
    </div>
    </div>
    </div>
</div>
@endsection

@section('script')


<script type="text/javascript">
function ChooseAvatar1(){$("input[name='images[]']").click()}


function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
	var output = document.getElementById('avatar');
	output.src = URL.createObjectURL(event.target.files[0]);
};

$(document).on('click','.add_times',function(){
	$('.times').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="date" name="times[]" class="form-control" placeholder="المواعيد" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_times" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_times',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})


$(document).on('click','.add_watch',function(){
	$('.watch').append(
		`
		<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
			<div class="row">
				<div class="col-sm-8" style="padding-left: 5px ">
					<input type="text" name="watch[]" class="form-control" placeholder="المواعيد" >
				</div>
				<div class="col-sm-1" style="padding: 0 ">
					<button type="button" class="btn btn-danger btn-block remove_watch" data-code="${Date.now()}">
						<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
					</button>
				</div>
			</div>
		</div>
		`
	);
})

$(document).on('click','.remove_watch',function(){
	var cla = '.father'+$(this).data('code');
	$(cla).remove();
})


    $(document).on('click','.add_preparation',function(){
            $('.preparation').append(
                `
                <div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
                    <div class="row">
                      
                            <div class="col-sm-5" style="padding: 0 15px 0 3px">
                                <input type="text" name="kind[]" class="form-control" placeholder=" النوع" >
                            </div>
                            
                            <div class="col-sm-5" style="padding: 0 15px 0 3px">
                                <input type="text" name="price[]" class="form-control" placeholder=" السعر" >
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
		var cla = '.father'+$(this).data('code');
		$(cla).remove();
	})

    ////image
// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {

if (input.files) {
    var filesAmount = input.files.length;

    for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();

        reader.onload = function(event) {
            $($.parseHTML('<img class="img-fluid mb-2  bounceIn">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
        }

        reader.readAsDataURL(input.files[i]);
    }
}

};

$('#gallery1').on('change', function() {
imagesPreview(this, 'div.gallery');
});


// get sub sections
$(document).on('change','.country', function(){

var data = {
country    : $(this).val(),
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-cities') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        $('.cities').html('')
        $('.cities').append(`
        `);
        $.each(s,function(k,v){
        $('.cities').append(`
            <option value="${v.id}">${v.name}</option>
        `);
    })
    }});

});
</script>

@endsection


