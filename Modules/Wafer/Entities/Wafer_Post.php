<?php

namespace Modules\Wafer\Entities;

use Illuminate\Database\Eloquent\Model;

class Wafer_Post extends Model
{
    protected $table = 'wafer_posts';

    public function Section()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Section', 'section_id', 'id');
    } 

    public function WaferFarmer()
    {
        return $this->belongsTo('Modules\Wafer\Entities\Wafer_Farmer', 'farm_id', 'id');
    } 

    public function WaferOrders()
    {
        return $this->hasMany('Modules\Wafer\Entities\Wafer_Order', 'post_id', 'id');
    }

}
