<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Quizz_Result extends Model
{
    protected $table = 'cources_quizz_result';

    public function CourseQuizz()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Quizz', 'quizz_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function CourseQuizzAnswers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Answer', 'result_id', 'id');
    }

  
}
