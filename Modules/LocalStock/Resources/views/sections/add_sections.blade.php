@extends('layouts.app')

@section('style')

<style type="text/css">
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

	#gallery-photo-add {
    display: inline-block;
    position: absolute;
    z-index: 1;
    width: 100%;
    height: 50px;
    top: 0;
    left: 0;
    opacity: 0;
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
                <h5 class="m-0" style="display: inline;">إضافة قسم جديد للبورصة<i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
              </div>

              <div class="card-body">
                <form action="{{route('storelocalstocksectionss')}}" method="post" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="row">

                        
                      <div class="col-sm-4 marbo">
                          
                          <label>إختيار صورة <span class="text-danger"> * </span></label>
                            <input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;" required>
                            <img src="{{asset('dist/img/placeholder2.png')}}" style="width: 100%;height:200px" onclick="ChooseAvatar()" id="avatar">
                        </div>
                        <div class="col-sm-8">
                          <div class="row">
                            <div class="col-sm-12">
                              <label>إسم القسم</label> <span class="text-danger">*</span>
                              <input type="text" name="name" class="form-control" placeholder="إسم القسم " required="" style="margin-bottom: 10px">
                            </div>
                           
 
                            @foreach($main_sections as $value)
                            <div class="col-sm-2 result_style">
                              <input style="" type="checkbox" name="sections[]" value="{{$value->id}}}">
                              <label class="text-info">{{$value->name}}</label>
                            </div>
                            @endforeach
                            {{-- columns --}}
                            <div class="col-sm-12">
                              <label> العواميد <span class="text-danger">*</span></label>
                              <div class="row columns" >
                                <div class="col-sm-12">
                                  <div class="row">
                                    <div class="col-sm-8" style="padding-left: 5px ">
                                      <input type="text" name="column_name[]" class="form-control" placeholder="اسم العمود">
                                    </div>  
                                    <div class="col-sm-1" style="padding: 0 ">
                                      <button type="button" class="btn btn-primary btn-block add_columns">
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
                       

                      
              
                       
                        <div class="col-sm-12 marbo" style="margin-top: 10px">
                          <label> ملاحظات</label> <span class="text-primary">*</span>
                          <textarea class="form-control" rows="5" name="note" class="form-control" placeholder=" ملاحظات "  style="height: 200px;width: 100%;"></textarea>
                         
                        </div>
                      

                      <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
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
            <p>هذه الصفحة خاصة  باضافة قسم فرعي</p>
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

	
$(document).on('click','.add_columns',function(){
		$('.columns').append(
			`
			<div class="col-sm-12 father${Date.now()}" style="margin-top:10px">
				<div class="row">
					<div class="col-sm-8" style="padding-left: 5px ">
						<input type="text" name="column_name[]" class="form-control" placeholder="إسم العمود"  required="">
					</div>
					<div class="col-sm-1" style="padding: 0 ">
						<button type="button" class="btn btn-danger btn-block remove_columns" data-code="${Date.now()}">
							<i style="margin: 0px -7px " class="fas fa-minus-circle"></i>
						</button>
					</div>
				</div>
			</div>
			`
		);
	})

	$(document).on('click','.remove_columns',function(){
		var cla = '.father'+$(this).data('code');
		$(cla).remove();
	})


</script>
@endsection


