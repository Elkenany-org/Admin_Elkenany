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
<div class="row">
  <div class="col-sm-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h6 class="m-0" style="display: inline;">   تعديل السؤال :<span class="text-primary"> {{$question->question}} </span></h6>
      </div>
      <div class="card-body">
        <form action="{{route('updatequestion')}}" method="post" enctype="multipart/form-data">
          {{csrf_field()}}
          <div class="row">
            <input type="hidden" name="id" value="{{$question->id}}">
            <div class="col-sm-12" style="margin-top: 10px">
              <div class="from-group">
                <label>  السؤال</label> <span class="text-danger">*</span>
                <input type="text" name="question" value="{{$question->question}}" class="form-control" placeholder="  السؤال " required style="margin-bottom: 10px"></br>
              </div>
            </div>
            @foreach($question->CourseQuizzQuestionAnswers as $key => $value)
            @if($value->correct == 1)
            <input type="hidden" name="aid[]" value="{{$value->id}}">
            <div class="col-sm-6" style="margin-top: 10px">
              <div class="from-group">
                <label class="text-primary">  الإجابة الصحيحة</label> <span class="text-danger">*</span>
                <input type="text" name="answer[]" value="{{$value->answer}}" class="form-control" placeholder="  الاجابة الصحيحة " required style="margin-bottom: 10px"></br>
              </div>
            </div>
            @endif

            @if($value->correct == 0)
            <input type="hidden" name="aid[]" value="{{$value->id}}">
            <div class="col-sm-6" style="margin-top: 10px">
              <div class="from-group">
                <label>  إجابة خاطئة</label> <span class="text-danger">*</span>
                <input type="text" name="answer[]" value="{{$value->answer}}" class="form-control" placeholder="  اجابة خاطئة " required style="margin-bottom: 10px"></br>
              </div>
            </div>
            @endif
            @endforeach
          </div>
          {{-- submit --}}
          <button style="width: 50%; margin-left: auto; margin-top:30px; margin-right: auto; " class="btn btn-outline-primary btn-block">حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script type="text/javascript">



</script>

@endsection


