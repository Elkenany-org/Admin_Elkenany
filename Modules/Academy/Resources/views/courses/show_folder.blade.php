@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-body">
      <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

        {{-- videos --}}
        <li class="nav-item">
          <a class="nav-link active" id="custom-content-below-videos-tab" data-toggle="pill" href="#custom-content-below-videos" role="tab" aria-controls="custom-content-below-videos" aria-selected="false"> الفديوهات</a>
        </li>

        {{-- quizze --}}
        <li class="nav-item">
          <a class="nav-link" id="custom-content-below-quizze-tab" data-toggle="pill" href="#custom-content-below-quizze" role="tab" aria-controls="custom-content-below-quizze" aria-selected="false"> الاختبارات</a>
        </li>

      </ul>
    </div>
    <div class="tab-content" id="custom-content-below-tabContent">
      {{-- videos --}}
      <div class="tab-pane fade show active"  style="padding-top: 10px;" id="custom-content-below-videos" role="tabpanel" aria-labelledby="custom-content-below-videos-tab">
        <div class="row">
          <div class="col-sm-12">
              <div class="card-header">
                <h5 class="m-0" style="display: inline;">قائمة  الفديوهات </h5>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                  إضافة فديو 
                  <i class="fas fa-plus"></i>
                </button>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-hover table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>الفديو</th>
                    <th>العنوان</th>
                    <th> التاريخ</th>
                    <th>التحكم</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($folder->CourseOfflinevideos as $key => $value)
                    <tr>
                      <td style="padding-top: 60px">{{$key+1}}</td>
                      <td>
                      <video class="video" width="200" style="" controls  >
                        <source src="{{asset('uploads/videos/'.$value->video)}}" id="video_here">
                      </video></td>
                      <td style="padding-top: 60px">{{$value->title}}</td>
                      
                      <td style="padding-top: 60px"> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                      <td style="padding-top: 60px">
                      <a href="" 
                      class="btn btn-info btn-sm edit"
                      data-toggle="modal"
                      data-target="#modal-update"
                      data-id    = "{{$value->id}}"
                      data-title = "{{$value->title}}"
                      data-desc  = "{{$value->desc}}"

                      >  تعديل <i class="fas fa-edit"></i></a>
                        <form action="{{route('Deletevideo')}}" method="post" style="display: inline-block;">
                          {{csrf_field()}}
                          <input type="hidden" name="id" value="{{$value->id}}">
                          <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
          

            {{-- add folder modal --}}
            <div class="modal fade" id="modal-primary">
              <div class="modal-dialog">
                <div class="modal-content bg-primary">
                <div class="modal-header">
                  <h4 class="modal-title">إضافة فديو جديد</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <form action="{{route('storeovideos')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="folder_id" value="{{$folder->id}}">
                    <input type="hidden" name="offline_id" value="{{$folder->offline_id}}">
                    <label>   العنوان</label> <span class="text-danger">*</span>
                    <input type="text" name="title" class="form-control" placeholder="  العنوان " required="" style="margin-bottom: 10px"></br>

                    <label>الوصف <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" name="desc"  placeholder="الوصف" required></textarea></br>

                    <label style="margin-top: 10px;display: block;" >إختيار فديو <span class="text-danger"> * </span></label>
                    <input type="file" name="video" accept="video/*" required onchange="loadAvatar(event)" style="display: none;">
                    <img src="{{asset('dist/img/video-icon.png')}}" style="display: block;width: 180px;height: 150px;cursor: pointer;" onclick="ChooseAvatar()" id="avatar">

                    <video class="video" width="300" style="display: none;" controls >
                        <source src="" id="video_here">
                    </video>

                    <button type="submit" id="submit" style="display: none;"></button>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-outline-light save">حفظ</button>
                  <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
                </div>
              </div>
            </div>


            {{-- edit folder modal --}}
            <div class="modal fade" id="modal-update">
              <div class="modal-dialog">
              <div class="modal-content bg-info">
                <div class="modal-header">
                <h4 class="modal-title">تعديل المجلد : <span class="item_name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                <form action="{{route('updatevideo')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_id" value="">
                    <label>   العنوان</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_title" class="form-control" placeholder="  العنوان " required="" style="margin-bottom: 10px"></br>

                    <label>الوصف <span class="text-danger">*</span></label>
                    <textarea class="form-control" rows="3" name="edit_desc"  placeholder="الوصف" required></textarea></br>

                    <button type="submit" id="update" style="display: none;"></button>
                </form>
                </div>
                <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light update">تحديث</button>
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
              </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      
      {{-- quizze --}}
      <div class="tab-pane fade"  style="padding-top: 10px;" id="custom-content-below-quizze" role="tabpanel" aria-labelledby="custom-content-below-quizze-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة  الاختبارات </h5>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary1" style="float: left;">
                  إضافة اختبار 
                  <i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th>#</th>
                <th>العنوان</th>
                <th> التاريخ</th>
                <th>التحكم</th>
              </tr>
              </thead>
              <tbody>
              @foreach($folder->CourseQuizzs as $key => $value)
                <tr>
                <td>{{$key+1}}</td>
                <td>{{$value->title}}</td>
                <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                <td>
                <a href="" 
                  class="btn btn-info btn-sm edit1"
                  data-toggle     ="modal"
                  data-target     ="#modal-updateq"
                  data-id         = "{{$value->id}}"
                  data-title      = "{{$value->title}}"
                  data-residuum   = "{{$value->residuum}}"
                  data-accepted   = "{{$value->accepted}}"
                  data-good       = "{{$value->good}}"
                  data-very       = "{{$value->very_good}}"
                  data-excellent  = "{{$value->excellent}}"
                  data-folder     = "{{$value->folder_id}}"
                  >  تعديل <i class="fas fa-edit"></i></a>
                  <a href="{{route('Editquizze',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تفاصيل الاختبار <i class="fas fa-eye"></i></a>
                  <form action="{{route('Deletequizze')}}" method="post" style="display: inline-block;">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$value->id}}">
                    <button class="btn btn-danger btn-sm delete" type="submit">  حذف <i class="fas fa-trash"></i></button>
                  </form>
                  </td>
                </tr>
              @endforeach
              </tfoot>
              </table>
            </div>
            <!-- /.card-body -->

            {{-- add folder modal --}}
            <div class="modal fade" id="modal-primary1">
              <div class="modal-dialog">
                <div class="modal-content bg-primary">
                <div class="modal-header">
                  <h4 class="modal-title">إضافة اختبار جديد</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <form action="{{route('storequizze')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$folder->courses_id}}">
                    <label> عنوان الاختبار</label> <span class="text-danger">*</span>
                    <input type="text" name="quizze_title" class="form-control" placeholder=" عنوان الاختبار " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الرسوب</label> <span class="text-danger">*</span>
                    <input type="number" name="residuum" class="form-control" placeholder=" نسبة الرسوب " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة المقبول</label> <span class="text-danger">*</span>
                    <input type="number" name="accepted" class="form-control" placeholder=" نسبة المقبول " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الجيد</label> <span class="text-danger">*</span>
                    <input type="number" name="good" class="form-control" placeholder=" نسبة الجيد " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الجيد جدا</label> <span class="text-danger">*</span>
                    <input type="number" name="very_good" class="form-control" placeholder=" نسبة الجيد جدا " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الامتياذ</label> <span class="text-danger">*</span>
                    <input type="number" name="excellent" class="form-control" placeholder=" نسبة الامتياذ " required="" style="margin-bottom: 10px"></br>
                    <label>  المجلدات <span class="text-danger">*</span></label>
                    <select name="folder_id" class="form-control">
                      <option value="" disabled selected>إختيار مجلد </option>
                      @foreach($folders as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                    <button type="submit" id="submitq" style="display: none;"></button>
                    
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-outline-light store">حفظ</button>
                  <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
                </div>
              </div>
            </div>

            {{-- edit quizze modal --}}
            <div class="modal fade" id="modal-updateq">
              <div class="modal-dialog">
                <div class="modal-content bg-info">
                <div class="modal-header">
                  <h4 class="modal-title">تعديل اختبار : <span class="item_name"></span></h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                  <form action="{{route('updatequizze')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="edit_quizze_id" value="">
                    <label> عنوان الاختبار</label> <span class="text-danger">*</span>
                    <input type="text" name="edit_quizze_title" class="form-control" placeholder=" عنوان الاختبار " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الرسوب</label> <span class="text-danger">*</span>
                    <input type="number" name="edit_residuum" class="form-control" placeholder=" نسبة الرسوب " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة المقبول</label> <span class="text-danger">*</span>
                    <input type="number" name="edit_accepted" class="form-control" placeholder=" نسبة المقبول " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الجيد</label> <span class="text-danger">*</span>
                    <input type="number" name="edit_good" class="form-control" placeholder=" نسبة الجيد " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الجيد جدا</label> <span class="text-danger">*</span>
                    <input type="number" name="edit_very" class="form-control" placeholder=" نسبة الجيد جدا " required="" style="margin-bottom: 10px"></br>
                    <label> نسبة الامتياذ</label> <span class="text-danger">*</span>
                    <input type="number" name="edit_excellent" class="form-control" placeholder=" نسبة الامتياذ " required="" style="margin-bottom: 10px"></br>
                    <label>  المجلدات <span class="text-danger">*</span></label>
                    <select name="edit_folder_id" class="form-control">
                      <option value="" selected>إختيار مجلد </option>
                      @foreach($folders as $value)
                        <option value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                    </select>
                    <button type="submit" id="updateq" style="display: none;"></button>
                  </form>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-outline-light updateq">تحديث</button>
                  <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
                </div>
                </div>
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

$('.save').on('click',function(){
        $('#submit').click();
})

function ChooseAvatar(){

  $("input[name='video']").click();
 
  }


document.querySelector("input[type=file]")
.onchange = function(event) {
  $('.video').css("display", "block");
  let file = event.target.files[0];
  let blobURL = URL.createObjectURL(file);
  document.querySelector("video").src = blobURL;
}


//edit video
$('.edit').on('click',function(){
        var id      = $(this).data('id')
        var title   = $(this).data('title')
        var desc    = $(this).data('desc')



     

        
        $('.item_name').text(title)
        $("input[name='edit_id']").val(id)
        $("input[name='edit_title']").val(title)
        $("textarea[name='edit_desc']").html(desc)


       
    })

    // update video
    $('.update').on('click',function(){
        $('#update').click();
    })

    
$('.store').on('click',function(){
        $('#submitq').click();
})

// update quizz
$('.updateq').on('click',function(){
        $('#updateq').click();
	})

	
//edit quizz
$('.edit1').on('click',function(){
	var id          = $(this).data('id')
	var title       = $(this).data('title')
	var residuum    = $(this).data('residuum')
	var accepted    = $(this).data('accepted')
	var good        = $(this).data('good')
	var very        = $(this).data('very')
	var excellent   = $(this).data('excellent')
	var folder      = $(this).data('folder')
	
	$("input[name='edit_quizze_id']").val(id)
	$("input[name='edit_quizze_title']").val(title)
	$("input[name='edit_residuum']").val(residuum)
	$("input[name='edit_accepted']").val(accepted)
	$("input[name='edit_good']").val(good)
	$("input[name='edit_very']").val(very)
	$("input[name='edit_excellent']").val(excellent)

	$("select[name='edit_folder_id'] > option").each(function() {
            if($(this).val() == folder)
            {
              $(this).attr("selected","")
            }
          });


})

</script>
@endsection