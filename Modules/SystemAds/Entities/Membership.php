<?php

namespace Modules\SystemAds\Entities;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $table = 'system_ads_membership';

    protected $fillable = ['ads_count','main','sub','start_date','end_date'];
    
    public function AdsUser()
    {
        return $this->belongsTo('Modules\SystemAds\Entities\Ads_User', 'ads_user_id', 'id');
    }

    public function Company()
    {
        return $this->belongsTo('Modules\Guide\Entities\Company', 'company_id', 'id');
    }
    
}
