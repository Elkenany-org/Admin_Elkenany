<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Doctor_Orders extends Model
{
    protected $table = 'doctors_orders';

    public function Doctor()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Doctor', 'doctor_id', 'id');
    }
    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
    public function DoctorServices()
    {
        return $this->belongsTo('Modules\Consultants\Entities\Doctor_Services', 'service_id', 'id');
    }
}
