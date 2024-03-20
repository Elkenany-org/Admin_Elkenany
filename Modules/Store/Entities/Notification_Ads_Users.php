<?php

namespace Modules\Store\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Notification_Ads_Users extends Model
{
    protected $table = 'notification_ads_users';


    public function storeAds()
    {
        return $this->belongsTo('Modules\Store\Entities\Store_Ads', 'ads_id', 'id');
    }
    public function NotificationAds()
    {
        return $this->belongsTo('Modules\Notification\Entities\Notification_Ads', 'notification_ads_id', 'id');
    }
}
