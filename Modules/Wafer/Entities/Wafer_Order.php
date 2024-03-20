<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Order extends Model
{
    protected $table = 'wafer_orders';

    public function WaferFarmer()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Farmer', 'farm_id', 'id');
    } 

    public function WaferPost()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Post', 'post_id', 'id');
    } 

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function WaferCars()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Car', 'order_id', 'id');
    }

}
