<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\URL;
use Modules\Guide\Entities\Company;

class Notification_Ads extends Model
{
//    use HasFactory;



    protected $table = 'notification_ads';

    protected $fillable = ['message'];

//    public function NotificationAdsUsers()
//    {
//        return $this->hasMany('Modules\Notification\Entities\Notification_Ads_Users', 'notification_ads_id', 'id');
//    }
}
