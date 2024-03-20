<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Doctor_Rating extends Model
{
    protected $table = 'consultants_reating';

    public function Doctor()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Doctor', 'doctor_id', 'id');
    }
    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
}
