<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Local_Stock_Count extends Model
{
    protected $table = 'local_stock_count_change';

    public function Section()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Sub', 'section_id', 'id');
    }

}
