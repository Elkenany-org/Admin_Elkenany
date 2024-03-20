<?php

namespace Modules\Academy\Entities;

use Illuminate\Database\Eloquent\Model;

class Course_Comment extends Model
{
    protected $table = 'cources_comments';
   
    public function Course()
    {
        return $this->belongsTo('Modules\Academy\Entities\Course', 'courses_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function REPComments()
    {
        return $this->hasMany('Modules\Academy\Entities\REP_Comment', 'com_id', 'id');
    }
}
