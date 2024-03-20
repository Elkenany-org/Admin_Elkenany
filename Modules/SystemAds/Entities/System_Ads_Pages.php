<?php

namespace Modules\SystemAds\Entities;

use Illuminate\Database\Eloquent\Model;

class System_Ads_Pages extends Model
{
    protected $table = 'system_ads_pages';
    
    public function SystemAds()
    {
        return $this->belongsTo('Modules\SystemAds\Entities\System_Ads', 'ads_id', 'id');
    }

    
    
}
