<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;

class Store_Ads_Comment extends Model
{
    protected $table = 'store_ads_comments';
   
    public function StoreAds()
    {
        return $this->belongsTo('Modules\Store\Entities\Store_Ads', 'ads_id', 'id');
    }

    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }
}
