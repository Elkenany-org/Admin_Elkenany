<?php

namespace Modules\Academy\Entities;
use Modules\Academy\Entities\Course_Quizz_Question_Answer;

use Illuminate\Database\Eloquent\Model;

class Course_Quizz_Question extends Model
{
    protected $table = 'cources_quizz_question';

    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function CourseQuizz()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz', 'quizz_id', 'id');
    } 

    public function CourseQuizzQuestionAnswers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Question_Answer', 'question_id', 'id');
    }

    public function CourseQuizzAnswers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Answer', 'question_id', 'id');
    }

    public function Lastans()
    {
        return Course_Quizz_Question_Answer::where('question_id',$this->id)->inRandomOrder()->get();
    } 
  
}
