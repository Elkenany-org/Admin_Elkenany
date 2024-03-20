<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;

class Store_Section extends Model
{
    protected $table = 'store_sections';
   
    public function StoreAds()
    {
        return $this->hasMany('Modules\Store\Entities\Store_Ads', 'section_id', 'id');
    }
}
