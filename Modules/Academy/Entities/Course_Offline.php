<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Offline extends Model
{
    protected $table = 'cources_offline';
   
    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function CourseOfflinevideos()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Offline_videos', 'offline_id', 'id');
    }

    public function CourseOfflineFolders()
    {
        return $this->hasMany('Modules\Academy\Entities\Course_Offline_Folder', 'offline_id', 'id');
    }
}
