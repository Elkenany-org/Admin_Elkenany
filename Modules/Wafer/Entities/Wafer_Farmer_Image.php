<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Farmer_Image extends Model
{
    protected $table = 'wafer_farmers_images';

    public function WaferFarmer()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Farmer', 'farm_id', 'id');
    } 
}
