<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Local_Stock_Columns extends Model
{
    protected $table = 'local_stock_columns';

    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id');
    }

    public function LocalStockDetials()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Detials', 'column_id', 'id');
    }
}
