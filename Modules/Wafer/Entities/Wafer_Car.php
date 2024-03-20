<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Car extends Model
{
    protected $table = 'wafer_order_cars';

    public function WaferOrder()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Order', 'order_id', 'id');
    } 


}
