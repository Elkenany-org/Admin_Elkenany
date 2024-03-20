<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Doctor_Link extends Model
{
    protected $table = 'doctors_links';

    public function Doctor()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Doctor', 'doctor_id', 'id');
    }
 
}
