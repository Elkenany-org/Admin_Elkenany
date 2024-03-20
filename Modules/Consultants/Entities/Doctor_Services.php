<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Doctor_Services extends Model
{
    protected $table = 'doctors_services_time';

    public function Doctor()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Doctor', 'doctor_id', 'id');
    }
    public function DoctorOrders()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Orders', 'service_id', 'id');
    }
}
