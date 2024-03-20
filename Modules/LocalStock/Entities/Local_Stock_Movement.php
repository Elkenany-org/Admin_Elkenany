<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Local_Stock_Movement extends Model
{
    protected $table = 'local_stock_movement';

    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id');
    }

    public function LocalStockMember()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Member', 'member_id', 'id');
    }

    public function LocalStockDetials()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Detials', 'movement_id', 'id');
    }
    
}
