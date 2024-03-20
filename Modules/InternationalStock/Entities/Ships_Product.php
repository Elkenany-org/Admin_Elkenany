<?php

namespace Modules\InternationalStock\Entities;

use Illuminate\Database\Eloquent\Model;

class Ships_Product extends Model
{
    protected $table = 'ships_products';

    public function Ships()
    {
        return $this->hasMany('Modules\InternationalStock\Entities\Ships', 'product_id', 'id');
    }

   
}
