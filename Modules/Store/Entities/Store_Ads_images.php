<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Store_Ads_images extends Model
{
    protected $table = 'store_ads_images';

    protected $appends = ['image_url'];

    public function StoreAds()
    {
        return $this->belongsTo('Modules\Store\Entities\Store_Ads', 'ads_id', 'id');
    }

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('/uploads/stores/alboum/').'/'.$this->image;
        }
        return "";
    }
}
