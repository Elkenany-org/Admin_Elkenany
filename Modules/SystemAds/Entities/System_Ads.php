<?php

namespace Modules\SystemAds\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class System_Ads extends Model
{
    protected $table = 'system_ads';

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return URL::to('uploads/full_images/').'/'.$this->image;

    }

    public function AdsUser()
    {
        return $this->belongsTo('Modules\SystemAds\Entities\Ads_User', 'ads_user_id', 'id');
    }

    public function SystemAdsPages()
    {
        return $this->hasMany('Modules\SystemAds\Entities\System_Ads_Pages', 'ads_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }
    
}
