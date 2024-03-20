<?php

namespace Modules\Consultants\Entities;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    public function Majors(){
        return $this->belongsToMany('Modules\Consultants\Entities\Major','doctors_majors','doctor_id','major_id');
    }

    public function SubSections(){
        return $this->belongsToMany('Modules\Consultants\Entities\Sub_Section','doctors_majors','doctor_id','sub_id');
    }

    public function DoctorServices()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Services', 'doctor_id', 'id');
    }
    public function DoctorOrders()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Orders', 'doctor_id', 'id');
    }

    public function DoctorRatings()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Rating', 'doctor_id', 'id');
    }

    public function DoctorLinks()
    {
        return $this->hasMany('Modules\Consultants\Entities\Doctor_Link', 'doctor_id', 'id');
    }

}
