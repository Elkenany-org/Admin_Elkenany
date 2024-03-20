@extends('layouts.app')

@section('style')
<style type="text/css">
	#avatar{
		width: 200px;
	}
	#avatar:hover{
		width: 200px;
		cursor: pointer;
	}
	
</style>
@endsection

@section('content')

<div class="container-fluid">
  <div class="card card-primary card-outline">

    <div class="card-body">
      <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">

        {{-- question --}}
        <li class="nav-item">
          <a class="nav-link active" id="custom-content-below-question-tab" data-toggle="pill" href="#custom-content-below-question" role="tab" aria-controls="custom-content-below-question" aria-selected="true"> اسئلة الاختبار</a>
        </li>

        {{-- result --}}
        <li class="nav-item">
          <a class="nav-link" id="custom-content-below-result-tab" data-toggle="pill" href="#custom-content-below-result" role="tab" aria-controls="custom-content-below-result" aria-selected="false"> نتائج الاختبار</a>
        </li>

      </ul>
    </div>
    <div class="tab-content" id="custom-content-below-tabContent">

      {{-- question --}}
      <div class="tab-pane fade show active" id="custom-content-below-question" role="tabpanel" aria-labelledby="custom-content-below-question-tab">

        <div class="row">
          <div class="col-sm-12">
            <div class="card-header">
              <h6 class="m-0" style="display: inline;">  اسئلة الاختبار:<span class="text-primary"> {{$quizze->title}} </span></h6>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-primary" style="float: left;">
                    إضافة سؤال اختياري 
                    <i class="fas fa-plus"></i>
              </button>
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-articl" style="float: left;margin-left:20px;">
                    إضافة سؤال نظري 
                    <i class="fas fa-plus"></i>
              </button>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th> السؤال</th>
                    <th> النوع</th>
                    <th>التاريخ</th>
                    <th>التحكم</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($quizze->CourseQuizzQuestions as $key => $value)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$value->question}}</td>
                    <td>
                    @if($value->type === 'choice' )
                    اختياري
                    @endif
                    @if($value->type === 'articl' )
                      نظري
                    @endif
                    </td>
                    <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                    <td>
                    <a href="{{route('Editquestion',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  تعديل <i class="fas fa-edit"></i></a>
                      <form action="{{route('Deletequestion')}}" method="post" style="display: inline-block;">
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
          </div>


          
          {{-- add question modal --}}
          <div class="modal fade" id="modal-primary">
            <div class="modal-dialog">
              <div class="modal-content bg-primary">
              <div class="modal-header">
                <h4 class="modal-title">إضافة سؤال جديد</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <form action="{{route('storequestion')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <input type="hidden" name="courses_id" value="{{$quizze->courses_id}}">
                  <input type="hidden" name="quizz_id" value="{{$quizze->id}}">
                  <label>  السؤال</label> <span class="text-danger">*</span>
                  <input type="text" name="question" class="form-control" placeholder="  السؤال " required style="margin-bottom: 10px"></br>
                  <label>  الاجابة الصحيحة</label> <span class="text-danger">*</span>
                  <input type="text" name="correct_answer" class="form-control" placeholder=" الاجابة الصحيحة " required style="margin-bottom: 10px"></br>
                  <label>  اجابة خاطئة</label> <span class="text-danger">*</span>
                  <input type="text" name="answer[]" class="form-control" placeholder="  اجابة خاطئة " required style="margin-bottom: 10px"></br>
                  <label>  اجابة خاطئة</label> <span class="text-danger">*</span>
                  <input type="text" name="answer[]" class="form-control" placeholder="  اجابة خاطئة " required style="margin-bottom: 10px"></br>
                  <label>  اجابة خاطئة</label> <span class="text-danger">*</span>
                  <input type="text" name="answer[]" class="form-control" placeholder="  اجابة خاطئة " required style="margin-bottom: 10px"></br>

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


          {{-- add question articl modal --}}
          <div class="modal fade" id="modal-articl">
            <div class="modal-dialog">
              <div class="modal-content bg-primary">
              <div class="modal-header">
                <h4 class="modal-title">إضافة سؤال جديد</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              </div>
              <div class="modal-body">
                <form action="{{route('storequestionarticl')}}" method="post" enctype="multipart/form-data">
                  {{csrf_field()}}
                  <input type="hidden" name="courses_id" value="{{$quizze->courses_id}}">
                  <input type="hidden" name="quizz_id" value="{{$quizze->id}}">
                  <label>  السؤال</label> <span class="text-danger">*</span>
                  <input type="text" name="question" class="form-control" placeholder="  السؤال " required style="margin-bottom: 10px"></br>

                  <button type="submit" id="submit1" style="display: none;"></button>
                </form>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-light store">حفظ</button>
                <button type="button" class="btn btn-outline-light" data-dismiss="modal">إغلاق</button>
              </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      {{-- result --}}
      <div class="tab-pane fade"  id="custom-content-below-result" role="tabpanel" aria-labelledby="custom-content-below-result-tab">
        <div class="row">
          <div class="col-sm-12">
            <div class="card-header">
            <h6 class="m-0" style="display: inline;">  نتائج الاختبار:<span class="text-primary"> {{$quizze->title}} </span></h6>
            </div>
            <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th>#</th>
                <th> الاسم</th>
                <th> النتيجة</th>
                <th> النسبة</th>
                <th>التاريخ</th>
                <th>التحكم</th>
              </tr>
              </thead>
              <tbody>
              @foreach($quizze->CourseQuizzResults as $key => $value)
                <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$value->Customer->name}}</td>
                  <td>{{$value->result}}</td>
                  <td>{{$value->success_rate}}%</td>
                  <td> <span class="badge badge-success">{{Date::parse($value->created_at)->diffForHumans()}}</span></td>
                  <td>
                  <a href="{{route('showanswer',$value->id)}}" class="btn btn-primary btn-sm " type="submit">  مشاهدة الاجابات <i class="fas fa-eye"></i></a>
                  </td>
                </tr>
                @endforeach
              </tfoot>
            </table>
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

$('.store').on('click',function(){
        $('#submit1').click();
})
</script>

@endsection


