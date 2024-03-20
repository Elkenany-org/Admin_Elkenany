<?php

namespace Modules\LocalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Local_Stock_product extends Model
{
    protected $table = 'local_stock_products';


    public function LocalStockMembers()
    {
        return $this->hasMany('Modules\LocalStock\Entities\Local_Stock_Member', 'product_id', 'id');
    }
   
}
