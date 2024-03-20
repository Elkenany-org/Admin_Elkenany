<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Quizz_Answer extends Model
{
    protected $table = 'cources_quizz_answers';

    public function CourseQuizz()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz', 'quizz_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function CourseQuizzResults()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz_Result', 'result_id', 'id');
    } 

    public function CourseQuizzQuestionAnswers()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz_Question_Answer', 'answer_id', 'id');
    } 

    public function CourseQuizzQuestions()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz_Question', 'question_id', 'id');
    } 

  
}
