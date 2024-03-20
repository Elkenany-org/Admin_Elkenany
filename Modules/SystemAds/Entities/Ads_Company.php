<?php

namespace Modules\SystemAds\Entities;

use Illuminate\Database\Eloquent\Model;

class Ads_Company extends Model
{
    protected $table = 'ads_user_companies';
    
    public function AdsUser()
    {
        return $this->belongsTo('Modules\SystemAds\Entities\Ads_User', 'ads_user_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }
    
}
