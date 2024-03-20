<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Quizz_Question_Answer extends Model
{
    protected $table = 'cources_quizz_question_answers';

    public function CourseQuizzQuestions()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz_Question', 'question_id', 'id');
    } 

}
