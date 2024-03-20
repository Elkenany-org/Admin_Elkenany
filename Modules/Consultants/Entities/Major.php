<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';

    public function Doctor(){
        return $this->belongsToMany('Modules\Consultants\Entities\Doctor','doctors_majors','major_id','doctor_id');
    }

    public function SubSections()
    {
        return $this->hasMany('Modules\Consultants\Entities\Sub_Section', 'major_id', 'id');
    }
}
