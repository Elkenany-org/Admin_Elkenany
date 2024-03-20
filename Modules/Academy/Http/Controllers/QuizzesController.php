<?php

namespace Modules\Academy\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academy\Entities\Course;
use Modules\Academy\Entities\Course_Quizz;
use Modules\Academy\Entities\Course_Quizz_Question;
use Modules\Academy\Entities\Course_Quizz_Question_Answer;
use Modules\Academy\Entities\Course_Quizz_Result;
use Modules\Academy\Entities\Course_Quizz_Answer;
use Session;
use Image;
use File;


class QuizzesController extends Controller
{
    # store quizze 
    public function storequizze(Request $request)
    {
        $request->validate([
            'quizze_title' => 'required',
            'residuum'     => 'required',
            'accepted'     => 'required',
            'good'         => 'required',
            'very_good'    => 'required',
            'excellent'    => 'required',

        ]);

        $quizze = new Course_Quizz;
        $quizze->title       = $request->quizze_title;
        $quizze->residuum    = $request->residuum;
        $quizze->accepted    = $request->accepted;
        $quizze->good        = $request->good;
        $quizze->very_good   = $request->very_good;
        $quizze->excellent   = $request->excellent;
        $quizze->courses_id  = $request->id;
        $quizze->folder_id   = $request->folder_id;
        $quizze->save();


        MakeReport('بإضافة اختبار جديد ' .$quizze->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # edit quizze
    public function Editquizze($id)
    {
        $quizze = Course_Quizz::with('CourseQuizzQuestions','CourseQuizzResults.Customer')->where('id',$id)->first();
        return view('academy::courses.edit_quizzes',compact('quizze'));
    }

    # update quizze 
    public function updatequizze(Request $request)
    {
        $request->validate([
            'edit_quizze_title' => 'required',
            'edit_residuum'     => 'required',
            'edit_accepted'     => 'required',
            'edit_good'         => 'required',
            'edit_very'    => 'required',
            'edit_excellent'    => 'required',

        ]);

        $quizze = Course_Quizz::where('id',$request->edit_quizze_id)->first();
        $quizze->title       = $request->edit_quizze_title;
        $quizze->residuum    = $request->edit_residuum;
        $quizze->accepted    = $request->edit_accepted;
        $quizze->good        = $request->edit_good;
        $quizze->very_good   = $request->edit_very;
        $quizze->excellent   = $request->edit_excellent;
        $quizze->folder_id   = $request->edit_folder_id;
        $quizze->save();

        MakeReport('بتحديث اختبار  ' .$quizze->title);
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete quizze
    public function Deletequizze(Request $request)
    {

        $quizze = Course_Quizz::where('id',$request->id)->first();
        MakeReport('بحذف الاختبار '.$quizze->title);
        $quizze->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # store question 
    public function storequestion(Request $request)
    {
        $request->validate([
            'question'  => 'required',

        ]);

        $question = new Course_Quizz_Question;
        $question->question          = $request->question;
        $question->courses_id        = $request->courses_id;
        $question->quizz_id          = $request->quizz_id;
        $question->type              = 'choice';
        $question->save();

        $answer = new Course_Quizz_Question_Answer;
        $answer->answer         = $request->correct_answer;
        $answer->question_id    = $question->id;
        $answer->correct        = 1;
        $answer->save();

        foreach($request->answer as $answer)
        {
            $fanswer = new Course_Quizz_Question_Answer;
            $fanswer->answer         = $answer;
            $fanswer->question_id    = $question->id;
            $fanswer->save();
      
        }


        MakeReport('بإضافة  سؤال لاختبار ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # store question articl
    public function storequestionarticl(Request $request)
    {
        $request->validate([
            'question'  => 'required',

        ]);

        $question = new Course_Quizz_Question;
        $question->question          = $request->question;
        $question->courses_id        = $request->courses_id;
        $question->quizz_id          = $request->quizz_id;
        $question->type              = 'articl';
        $question->save();

        MakeReport('بإضافة  سؤال لاختبار ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # delete question
    public function Deletequestion(Request $request)
    {

        $question = Course_Quizz_Question::where('id',$request->id)->first();
        MakeReport('بحذف السؤال ');
        $question->delete();
        Session::flash('success','تم الحذف');
        return back();
    }

    # edit question
    public function Editquestion($id)
    {
        $question = Course_Quizz_Question::with('CourseQuizzQuestionAnswers')->where('id',$id)->first();
        return view('academy::courses.edit_question',compact('question'));
    }

    # update question 
    public function updatequestion(Request $request)
    {
        $request->validate([
            'question'  => 'required',

        ]);

        $question = Course_Quizz_Question::where('id',$request->id)->first();
        $question->question          = $request->question;
        $question->save();
        if($question->type == 'choice')
        {
            foreach(array_combine($request->aid, $request->answer) as $id => $answer)
            {
                Course_Quizz_Question_Answer::where('id',$id)->update(['answer' => $answer]);
            }
        }
       

        MakeReport('بتحديث  سؤال لاختبار ');
        Session::flash('success','تم الحفظ');
        return back();
    }

    # show answer
    public function showanswer($id)
    {
        $result = Course_Quizz_Result::with('CourseQuizzAnswers.CourseQuizzQuestionAnswers','CourseQuizzAnswers.Customer','CourseQuizzAnswers.CourseQuizzQuestions')->where('id',$id)->first();
        return view('academy::courses.answers',compact('result'));
    }

    # update answer 
    public function updateanswerc(Request $request)
    {


        $answer = Course_Quizz_Answer::where('id',$request->id)->first();
        $answer->state = 1;
        $answer->save();
        return back();
    }

    # update answer 
    public function updateanswerf(Request $request)
    {


        $answer = Course_Quizz_Answer::where('id',$request->id)->first();
        $answer->state = 0;
        $answer->save();
        return back();
    }

  
}
