<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Member_Count extends Model
{
    protected $table = 'local_stock_member_change';

    public function LocalStockMember()
    {
        return $this->belongsTo('Modules\LocalStock\Entities\Local_Stock_Member', 'member_id', 'id');
    }

}
