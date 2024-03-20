<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Store_Ads extends Model
{
    protected $table = 'store_ads';
    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if($this->image != ""){
            return URL::to('uploads/stores/alboum/'.$this->image);
        }
        return "";
    }
    public function Customer()
    {
        return $this->belongsTo('Modules\Store\Entities\Customer', 'user_id', 'id');
    }

    public function User()
    {
        return $this->belongsTo('App\User', 'admin_id', 'id');
    }

    public function StoreAdsimages()
    {
        return $this->hasMany('Modules\Store\Entities\Store_Ads_images', 'ads_id', 'id');
    }

    public function StoreAdsComments()
    {
        return $this->hasMany('Modules\Store\Entities\Store_Ads_Comment', 'ads_id', 'id');
    }

    public function StoreSection()
    {
        return $this->belongsTo('Modules\Store\Entities\Store_Section', 'section_id', 'id');
    }


    
}
