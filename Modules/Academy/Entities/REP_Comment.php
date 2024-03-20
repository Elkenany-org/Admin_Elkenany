<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class REP_Comment extends Model
{
    protected $table = 'replaies';
   
    public function CourseComment()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course_Comment', 'com_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
}
