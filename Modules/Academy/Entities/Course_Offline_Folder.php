<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Academy\Entities\User_videos;
use Auth;
class Course_Offline_Folder extends Model
{
    protected $table = 'offline_folder';
   

    public function CourseOffline()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Offline', 'offline_id', 'id');
    } 

    public function CourseOfflinevideos()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Offline_videos', 'folder_id', 'id');
    }

    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function CourseQuizzs()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Quizz', 'folder_id', 'id');
    }

    public function usvids()
    {
        return User_videos::where('folder_id',$this->id)->where('status','1')->where('user_id', Auth::guard('customer')->user()->id)->latest()->get();
         
    } 
}
