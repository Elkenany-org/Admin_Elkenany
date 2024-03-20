<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Academy\Entities\Course_user;
use Modules\Academy\Entities\User_videos;
use Modules\Academy\Entities\Course_Quizz;
use Modules\Academy\Entities\Course_Offline_videos;
use Modules\Academy\Entities\Course_Quizz_Result;
use Auth;

class Course extends Model
{
    protected $table = 'cources';
   
    public function CourseLive()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Live', 'courses_id', 'id');
    }

    public function CourseMeeting()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Meeting', 'courses_id', 'id');
    }

    public function CourseOffline()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Offline', 'courses_id', 'id');
    }

    public function CourseQuizz()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz', 'courses_id', 'id');
    }

    public function CourseQuizzQuestions()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz_Question', 'courses_id', 'id');
    }

    public function CourseComments()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Comment', 'courses_id', 'id');
    }

    public function CourseOfflineFolders()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Offline_Folder', 'courses_id', 'id');
    }

    public function Courseusers()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_user', 'courses_id', 'id');
    }

    public function Uservideos()
    {
        return $this->hasMany('Modules\Academy\Entities\User_videos', 'courses_id', 'id');
    }

    public function uses()
    {
        return User_videos::where('courses_id',$this->id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->latest()->get();
         
    }
    public function resl()
    {
        $quize = Course_Quizz::where('courses_id',$this->id)->pluck('id')->toArray();

        return Course_Quizz_Result::whereIn('quizz_id',$quize)->where('user_id', Auth::guard('customer')->user()->id)->get();
         
    }
  
}
