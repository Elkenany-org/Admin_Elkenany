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
</style>
@endsection
@section('content')
<div class="row">
		<div class="col-sm-12">
          <form action="{{route('Storehomeimage')}}" method="post" enctype="multipart/form-data">
              {{csrf_field()}}
                  <div class="card  card-outline">
                  <div class="card-header">
                      <h5 class="m-0" style="display: inline;" style="float: right">اضافة صور</h5>
                      <div class="btn btn-primary" style="float: left;height:36px;">
                      <input type="file" name="images[]" id="gallery"  style="display: none;" accept="image/*" required multiple>
                      <label  style="cursor: pointer;font-size:14px;width: 100%;height: 100%;" onclick="ChooseAvatar1()" id="avatar1">  إضافة صور  <i class="fas fa-camera"></i></label>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="row">
                          <div class="col-sm-12 marbo img">
                              <div class="gallery">
                                  @foreach($gallary as $key => $value)
                                  <div class="filtr-item image col-sm-2"  style="position: relative;display: inline-block;" data-category="1" data-sort="white sample">
                                      <input type="hidden" name="idd" value="{{$value->id}}">
                                      <button type="button" data-id="{{$value->id}}" class="btn btn-danger btn-sm dele close"   style="z-index: 9999; position: absolute;background-color: red;display: none;border: none;font-size: 22px;padding: 3px 8px;color: #fff;border-radius: 50%;top: 30px;right: 20px;" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <img src="{{asset('uploads/main/'.$value->image)}}" class="img-fluid mb-2  bounceIn" alt="black sample"/>
                                  </div>
                                  @endforeach
                              </div>
                          </div>
                      </div>
                  </div>
                  <button style="width: 50%; margin-left: auto; margin-right: auto;margin-bottom:30px " type="submit" class="btn btn-outline-primary btn-block">إضافة</button>
              </div>
            
          </form> 
      </div>


</div>
@endsection

@section('script')
<script type="text/javascript">

function ChooseAvatar1(){$("input[name='images[]']").click()}	


$(".image").hover(function(){
  $(".dele",this).css("display", "block") }, 
  function(){
    $(".dele").css("display", "none");
});
$(".dele").hover(function(){
  $(this).css("display", "block") }, 
  function(){

});

$(".image").hover(function(){
  $(".note",this).css("display", "block") }, 
  function(){
    $(".note").css("display", "none");
});
$(".note").hover(function(){
  $(this).css({"display": "block", "background": "rgba(76, 175, 80, 1)"}) }, 
  function(){

});



$(function() {
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

$('#gallery').on('change', function() {
	imagesPreview(this, 'div.gallery');
});
});
	
$(".close").click(function(){
    var id = $(this).data("id");
    var $ele = $(this).parent();
    $.ajax(
    {
        url: "{{route('deleteehomeimage')}}",
        type: 'post',
        data: {
          _token: '{{ csrf_token() }}',
            "id": id,
        },
        success: function (){
          $ele.fadeOut().remove();
        }
    });
   
});

// update image
$('.update').on('click',function(){
	$('#update').click();
})
$('.edit').on('click',function(){
	var id         = $(this).data('id')
	var note       = $(this).data('note')

$("input[name='edit_id']").val(id)
$("input[name='edit_note']").val(note)
	
	
})
</script>
@endsection