@extends('layouts.app')

@section('content')
	<div class="row">
		<div class="col-sm-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h5 class="m-0" style="display: inline;">قائمة  الإجابات <i class="fas fa-exclamation-circle" style="cursor: pointer;color:#FFC107" data-toggle="modal" data-target="#modal-secondary"></i></h5>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>الاسم</th>
                  <th>السؤال</th>
                  <th>الاجابة</th>
                  <th> التصحيح</th>
                </tr>
                </thead>
                <tbody>
                @foreach($result->CourseQuizzAnswers as $key => $value)
	                <tr>
                    <td>{{$key+1}}</td>
	                  <td>{{$value->Customer->name}}</td>
                    <td>{{$value->CourseQuizzQuestions->question}}</td>
                    <td>
                    @if($value->CourseQuizzQuestions->type === 'choice' )
                    {{$value->CourseQuizzQuestionAnswers->answer}}
                    @endif
                    @if($value->CourseQuizzQuestions->type === 'articl' )
                    {{$value->articl}}
                    @endif
                    </td>
                    <td>
                    @if($value->state == null)
                    <form action="{{route('updateanswerc')}}" method="post" style="display: inline-block;">
                      {{csrf_field()}}
                      <input type="hidden" name="id" value="{{$value->id}}">
                      <button class="btn btn-success btn-sm" type="submit"><i class="fas fa-check"></i></button>
                    </form>
                    <form action="{{route('updateanswerf')}}" method="post" style="display: inline-block;">
                      {{csrf_field()}}
                      <input type="hidden" name="id" value="{{$value->id}}">
                      <button class="btn btn-danger btn-sm" type="submit">  <i class="fas fa-times"></i></button>
                    </form>
                    @endif
                    @if($value->state == '0')
                    الإجابة خاطئة
                    @endif
                    @if($value->state == '1')
                    الإجابة صحيحة
                    @endif
                    </td>
	                </tr>
                @endforeach
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          {{--warning--}}
          <div class="modal fade" id="modal-secondary">
            <div class="modal-dialog">
            <div class="modal-content bg-secondary">
              <div class="modal-body">
              <p>هذه الصفحة خاصة بالاجابات</p>
              </div>
            </div>
            </div>
          </div>
		</div>
	</div>
@endsection

@section('javascript')
<script type="text/javascript">

</script>
@endsection