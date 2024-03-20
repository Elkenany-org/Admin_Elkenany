@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 100%;
	}
	#avatar:hover{
		width: 100%;
		cursor: pointer;
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
<div class="container-fluid">
<div class="card card-primary card-outline">

<div class="card-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="card-header">
			<h6 class="m-0" style="display: inline;">تعديل  التقرير <span class="text-primary"> {{$news->title}} </span></h6>
			</div>
			<div class="card-body">
				<form action="{{route('Updateinterreports')}}" method="post" enctype="multipart/form-data" novalidate>
					<div class="row">
						{{csrf_field()}}
						<input type="hidden" name="id" value="{{$news->id}}">
						<div class="col-sm-12">
						<div class="row">
							{{-- details --}}
							{{-- title --}}
							<div class="col-sm-8 offset-2" style="margin-top: 10px">
								<div class="from-group">
									<label class="text-primary">عنوان التقرير: <span class="text-danger">*</span></label>
									<input type="text" name="title" class="form-control" value="{{$news->title}}" placeholder="عنوان التقرير" required="">
								</div>
							</div>
							
							<div class="col-sm-6 offset-3 marbo" style="margin-top: 10px">
								<label class="text-primary">إختيار صورة <span class="text-primary"> * </span></label><br>
								<input type="file" name="image" accept="image/*" onchange="loadAvatar(event)" style="display: none;">
								<img src="{{asset('uploads/news/avatar/'.$news->image)}}" onclick="ChooseAvatar()" id="avatar">
							</div>

              <div class="col-sm-6 offset-3 marbo" style="margin-top: 10px">
							<audio controls>
                <source src="{{asset('uploads/news/file/'.$news->file)}}" type="audio/mpeg">
       
              </audio>
							</div>
              <div class="col-sm-6 offset-3 marbo" style="margin-top: 10px">
              <label class="text-primary">إختيار ملف <span class="text-danger"> * </span></label><br>
									<input type="file" id="file" name="file">
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="row">
							{{-- desc --}}
							<div class="col-sm-12">
								<label class="text-primary">التفاصيل <span class="text-danger">*</span></label>
								<textarea class="form-control"  id="full-featured-non-premium" rows="10" name="desc" value="{{$news->desc}}" placeholder="التفاصيل" required>{{$news->desc}}</textarea>
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
</div>
</div>
@endsection

@section('script')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

tinymce.init({
  selector: 'textarea#full-featured-non-premium',
  plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
  imagetools_cors_hosts: ['picsum.photos'],
  menubar: 'file edit view insert format tools table help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
  toolbar_sticky: true,
  autosave_ask_before_unload: true,
  autosave_interval: '30s',
  autosave_prefix: '{path}{query}-{id}-',
  autosave_restore_when_empty: false,
  autosave_retention: '2m',
  image_advtab: true,
  link_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_list: [
    { title: 'My page 1', value: 'https://www.tiny.cloud' },
    { title: 'My page 2', value: 'http://www.moxiecode.com' }
  ],
  image_class_list: [
    { title: 'None', value: '' },
    { title: 'Some class', value: 'class-name' }
  ],
  importcss_append: true,
  file_picker_callback: function (callback, value, meta) {
    /* Provide file and text for the link dialog */
    if (meta.filetype === 'file') {
      callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
    }

    /* Provide image and alt text for the image dialog */
    if (meta.filetype === 'image') {
      callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
    }

    /* Provide alternative source and posted for the media dialog */
    if (meta.filetype === 'media') {
      callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
    }
  },
  templates: [
        { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
    { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
    { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
  ],
  template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
  template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
  height: 600,
  image_caption: true,
  quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
  noneditable_noneditable_class: 'mceNonEditable',
  toolbar_mode: 'sliding',
  contextmenu: 'link image imagetools table',
  skin: useDarkMode ? 'oxide-dark' : 'oxide',
  content_css: useDarkMode ? 'dark' : 'default',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
 });

</script>
<script type="text/javascript">
function ChooseAvatar1(){$("input[name='images[]']").click()}
function ChooseAvatar(){$("input[name='image']").click()}
var loadAvatar = function(event) {
var output = document.getElementById('avatar');
output.src = URL.createObjectURL(event.target.files[0]);
};

$(".image").hover(function(){
  $(".del",this).css("display", "block") }, 
  function(){
    $(".del").css("display", "none");
});
$(".del").hover(function(){
  $(this).css("display", "block") }, 
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
        url: "{{route('DeletenewsImage')}}",
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

</script>
@endsection


