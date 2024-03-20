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
  #avatar{
		width: 100%;
		height: 300px;
	}
	#avatar:hover{
		width: 100%;
		height: 300px;
		cursor: pointer;
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
        <h5 class="m-0" style="display: inline;" style="float: right">إضافة شركة جديدة <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5> 
    </div>
    <div class="card-body" style="padding: 0">
        <form id="regForm" action="{{route('storecompany')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
            
        
            <!-- tabs -->
            <div class="card-body">
                <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                
                    {{--  company --}}
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-content-below-add-tab" data-toggle="pill" href="#custom-content-below-add" role="tab" aria-controls="custom-content-below-add" aria-selected="false">  بيانات الشركة </a>
                    </li>

                    {{-- contect --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-contect-tab" data-toggle="pill" href="#custom-content-below-contect" role="tab" aria-controls="custom-content-below-contect" aria-selected="false">  التواصل</a>
                    </li>

                    {{--   social --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-social-tab" data-toggle="pill" href="#custom-content-below-social" role="tab" aria-controls="custom-content-below-social" aria-selected="false">  التواصل الاجتماعي</a>
                    </li>

                    {{-- address --}}
                    <li class="nav-item">
                        <a class="nav-link" id="custom-content-below-address-tab" data-toggle="pill" href="#custom-content-below-address" role="tab" aria-controls="custom-content-below-address" aria-selected="false">   العناوين الاضافية</a>
                    </li>


                </ul>
            </div>

            <div class="tab-content" id="custom-content-below-tabContent">

                {{--  company --}}
                <div class="tab-pane fade show active" style="padding-bottom: 50px;" id="custom-content-below-add" role="tabpanel" aria-labelledby="custom-content-below-add-tab">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-2" style="margin-top: 10px">
                                <label class="text-primary">إسم الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{old('name')}}" name="name" placeholder=" الاسم" required>
                            </div>
                         
                         
                            <div class="col-sm-2" style="margin-top: 10px">
                                <label class="text-primary">تلفون الشركة <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" value="{{old('manage_phone')}}" name="manage_phone" placeholder=" التلفون" required>
                            </div>
                            <div class="col-sm-2" style="margin-top: 10px">
                                <label class="text-primary">ايميل الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="{{old('manage_email')}}" name="manage_email" placeholder=" الايميل" required>
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
                          
                            <div class="col-sm-2" style="margin-top: 10px">
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
                                        <div class="col-sm-2">
                                            <span style="display: inline;width: 50%;">{{ $value->name }}</span>
                                            <input style="display: inline;width: 50%;" type="checkbox" class="major" name="sections[]" value="{{ $value->id }}">
                                           
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-4" >
                                <label style="margin-top: 10px" class="text-primary">  ابحث عن قسم فرعي <span class="text-danger">*</span></label>
                                <input type="search" class="form-control sec_search" name="sec_search">
                            </div>
                            {{-- sections --}}
                            <div class="col-sm-12" >
                                <label class="text-primary">الاقسام الفرعية </label> <span class="text-danger">*</span>
                                <div class="row sub_sections">

                                </div>
                            </div>
                            <div class="col-sm-6 m_top" style="margin-top: 10px">
                                <label class="text-primary">نبزة مختصرة <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="short_desc" placeholder="نبزة مختصرة " required>{{old('short_desc')}}</textarea>
                            </div>  
                            <div class="col-sm-6 m_top">
                                <label class="text-primary">عن الشركة <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="about" placeholder="عن الشركة" required>{{old('about')}}</textarea>
                            </div>  
                        </div>
                        <div class="row">
                            <div class="col-sm-6 marbo" style="margin-top: 10px">
                                <label class="text-primary" >إختيار صورة <span class="text-danger"> * </span></label><br>
                                <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
                                <img src="{{asset('dist/img/placeholder2.png')}}" style="width: 100%;height:200px" onclick="ChooseAvatar()" id="avatar">
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

                {{-- social --}}
                <div class="tab-pane fade" style="padding-top: 20px" id="custom-content-below-social" role="tabpanel" aria-labelledby="custom-content-below-social-tab">
                    <div class="container-fluid">
                        <div class="row">
                        @foreach($social as $media)
                            <input type="hidden" name="social_id[]" class="form-control" value="{{$media->id}}">
                                <div class="col-sm-12" style="margin-bottom: 10px">
                                    <div class="row">
                                        <div class="col-sm-1">
                                            <img src="{{$media->social_icon}}" style="width:75%;height:50px">
                                        </div>
                                        <div class="col-sm-2" style="line-height: 50px">
                                            {{$media->social_name}}
                                        </div>
                                        <div class="col-sm-6" >
                                            <input type="text" name="social_link[]" value="{{$media->social_link}}" class="form-control" placeholder="الرابط">
                                        </div>
                                    </div>
                                </div>
                        @endforeach
                        </div>
                    </div>
                </div>


                {{-- address --}}
                <div class="tab-pane fade" style="padding-top: 20px" id="custom-content-below-address" role="tabpanel" aria-labelledby="custom-content-below-address-tab">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-sm-12 marbo"  style="margin-top: 20px">
                                <label>  الفروع الاخري  <span class="text-danger">*</span></label>
                                <div class="row preparation">

                
                                    <div class="col-sm-4" style="padding: 0 15px 0 3px">
                                        <input type="text" name="loca[]" class="form-control" placeholder=" العنوان" >
                                    </div>
                                    
                                    <div class="col-sm-2" style="padding: 0 15px 0 3px">
                                        <input type="text" name="lat[]" class="form-control" placeholder=" lat" >
                                    </div>
                                    
                                    <div class="col-sm-2" style="padding: 0 15px 0 3px">
                                        <input type="text" name="long[]" class="form-control" placeholder=" long" >
                                    </div>
                                    <div class="col-sm-2" style="padding: 0 15px 0 3px">
                                        <select name="type[]" class="form-control" placeholder="" >
                                            <option value="">اختر نوع العنوان</option>
                                            @foreach(config('constants.address_type') as $name => $val)
                                                <option value="{{$name}}" {{$value->type == $name ? 'selected' : ''}}>{{$val}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1">

                                    </div>

                                    {{--  <div class="col-sm-1" style="padding: 0 0 0 1px">
                                        <button type="button" class="btn btn-danger btn-block" disabled>
                                            <i class="fas fa-minus-circle"></i>
                                        </button>
                                    </div>  --}}
                                    <div class="col-sm-1" >
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
        <p>هذه الصفحة خاصة   باضافة شركة</p>
        </div>
    </div>
    </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{asset('js/add_company_steps.js')}}"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCv2cGCkk7fn1CKKhqX6vA_VTF4UdnyLJ0&libraries=places&callback=initAutocomplete&language=ar&region=EGasync defer"></script>
<script type="text/javascript">
function ChooseAvatar1(){$("input[name='images[]']").click()}
// get sub sections
$(document).on('change','.section_id', function(){

var data = {
section_id    : $(this).val(),
_token        : $("input[name='_token']").val()
}


    $.ajax({
    url     : "{{ url('get-sub-sections') }}",
    method  : 'post',
    data    : data,
    success : function(s,result){
        $('.sub_sections').html('')
        $('.sub_sections').append(`
        `);
        $.each(s,function(k,v){
        $('.sub_sections').append(`
            <option value="${v.id}">${v.name}</option>
        `);
    })
    }});

});
</script>

<script type="text/javascript">
function ChooseAvatar1(){$("input[name='images[]']").click()}
// get sub sections
$(document).on('change','.major', function(){
    var id = $(this).val();
		if ($(this).is(':checked')) {
    var data = {
    section_id    : $(this).val(),
    _token        : $("input[name='_token']").val()
    }


        $.ajax({
        url     : "{{ url('get-sub-sections') }}",
        method  : 'post',
        data    : data,
        success : function(s,result){
    
            $.each(s,function(k,v){
            $('.sub_sections').append(`
            <div class="col-sm-2 result_style sub${id}">
					<input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
					<label class="text-info">${v.name}</label>
				</div>
            `);
        })
        }});

    }else{

$('.sub'+id).fadeOut().remove();
}});

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

// search companies
$(document).on('keyup','.sec_search', function(){
    var id = $('.major').val();
var data = {
    search     : $(this).val(),
    section_id : $('.major').val(),
    _token     : $("input[name='_token']").val()
}

$.ajax({
url     : "{{ url('get-sub-sections-guide-search') }}",
method  : 'post',
data    : data,
success : function(s,result){
    if ($('input[name="SubSections[]"]').is(':checked')) { 
        $('input[name="SubSections[]"]').not(':checked').parent().fadeOut().remove();
        $.each(s,function(k,v){
            if ($('input[name="SubSections[]"]:checked').val() != v.id) { 
    $('.sub_sections').append(`
                <div class="col-sm-2 result_style sub${id}">
                    <input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
                    <label class="text-info">${v.name}</label>
					
				</div>
    `);
}
})
    }else{
        $('.sub_sections').html('')
    $.each(s,function(k,v){
        if ($('input[name="SubSections[]"]:checked').val() != v.id) {
    $('.sub_sections').append(`
                <div class="col-sm-2 result_style sub${id}">
                    <input style="display: inline;width: 50%;" type="checkbox" name="SubSections[]" value="${v.id}">
                    <label class="text-info">${v.name}</label>
					
				</div>
    `);
}
})}
}});

});


    $(document).on('click','.add_preparation',function(){
            $('.preparation').append(
                `
                <div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
                    <div class="row">
                        <div class="col-sm-4" style="padding: 0 15px 0 3px">
                            <input type="text" name="loca[]" class="form-control" placeholder=" العنوان" >
                        </div>
                        
                        <div class="col-sm-2" style="padding: 0 15px 0 3px">
                            <input type="text" name="lat[]" class="form-control" placeholder=" lat" >
                        </div>
                        
                        <div class="col-sm-2" style="padding: 0 15px 0 3px">
                            <input type="text" name="long[]" class="form-control" placeholder=" long" >
                        </div>

                        <div class="col-sm-2" style="padding: 0 15px 0 3px">
                            <select name="type[]" class="form-control" placeholder="" >
                                <option value="">اختر نوع العنوان</option>
                                @foreach(config('constants.address_type') as $name => $val)
                                <option value="{{$name}}" {{$value->type == $name ? 'selected' : ''}}>{{$val}}</option>
								@endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                        </div>
                        <div class="col-sm-1">
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

</script>

@endsection


