<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class User_videos extends Model
{
    protected $table = 'videos_users';
   
    public function CourseOfflinevideos()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Offline_videos', 'video_id', 'id');
    } 

    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
}
