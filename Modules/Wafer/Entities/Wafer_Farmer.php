<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Farmer extends Model
{
    protected $table = 'wafer_farmers';

    public function Section()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Section', 'section_id', 'id');
    } 

    public function WaferPosts()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Post', 'farm_id', 'id');
    }

    public function WaferOrders()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Order', 'farm_id', 'id');
    }

    public function WaferFarmerOrders()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Farmer_Order', 'farm_id', 'id');
    }

    public function WaferFarmerImages()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Farmer_Image', 'farm_id', 'id');
    }
}
