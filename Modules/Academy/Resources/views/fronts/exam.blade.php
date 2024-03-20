
@extends('layouts.front')
@section('style')
<title>  الأكاديمية</title>
<style type="text/css">



</style> 
@endsection
@section('content')

<!-- page tabs -->
<div class="page__tabs container">
    <ul class="tabs__list">
        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_academy_courses') }}">كورسات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link " href="{{ route('front_my_courses') }}">كورساتك</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link active" href="{{ route('front_exams_courses') }}">الإمتحانات</a>
        </li>

        <li class="list__item list__item-4">
            <a class="list__link" href="{{ route('front_my_certificates') }}">الشهادات</a>
        </li>
    </ul>
</div>
<!-- page tabs -->

<div class="questions">
        <div class="container">
            <form action="{{route('exam_courses_answer')}}" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="quizz_id" value="{{$quize->id}}">
                <div class="row justify-content-center">
                    <div class="col-lg-9">
                        @foreach($quize->CourseQuizzQuestions as $value)
                            <div class="question-card">
                                <div class="form-group">
                                    <h5> {{$value->question}}</h5>
                                    <div class="row">
                                        @foreach($value->Lastans() as $val)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" id="exampleRadios{{$val->id}}" name="ans{{$value->id}}"
                                                        type="radio" value="{{$val->id}}">
                                                    <label class="form-check-label" for="exampleRadios{{$val->id}}">
                                                    {{$val->answer}}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach 
                                    </div>
                                </div>
                            </div>
                        @endforeach 
                    </div>
                </div>

                <button class="submit__btn" type="submit">إرسال الإجابة</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script src="{{asset('Front_End/js/jquery.nice-select.min.js')}}"></script>
<script>
    $('select').niceSelect();
</script>

@endsection