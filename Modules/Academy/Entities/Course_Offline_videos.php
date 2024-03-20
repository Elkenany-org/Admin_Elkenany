<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Offline_videos extends Model
{
    protected $table = 'cources_offline_videos';
   
    public function CourseOffline()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Offline', 'offline_id', 'id');
    } 

    public function CourseOfflineFolder()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Offline_Folder', 'folder_id', 'id');
    } 

    public function Uservideos()
    {
        return $this->hasMany('Modules\Academy\Entities\User_videos', 'video_id', 'id');
    }
}
