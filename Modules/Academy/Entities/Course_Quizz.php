<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Quizz extends Model
{
    protected $table = 'cources_quizz';

    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function CourseQuizzQuestions()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Question', 'quizz_id', 'id');
    }

    public function CourseQuizzResults()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Result', 'quizz_id', 'id');
    }
  
    public function CourseQuizzAnswers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Answer', 'quizz_id', 'id');
    }

    public function CourseOfflineFolder()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Offline_Folder', 'folder_id', 'id');
    } 
}
