<?php

namespace App\Observers;

//use App\System_Ads;
use Modules\Notification\Entities\Notification;
use Modules\SystemAds\Entities\System_Ads;

class AdsObserver
{
    /**
     * Handle the system_ ads "created" event.
     *
     * @param  \App\System_Ads  $systemAds
     * @return void
     */
    public function created(System_Ads $systemAds)
    {
        //
    }

    /**
     * Handle the system_ ads "updated" event.
     *
     * @param  \App\System_Ads  $systemAds
     * @return void
     */
    public function updated(System_Ads $systemAds)
    {
        if($systemAds->status == 1 && $systemAds->type == 'notification'){
            $notification = new Notification;
            $notification->duration_type = 'ads_sytem';
            $notification->title = $systemAds->title;
            $notification->body = $systemAds->desc;
            $notification->image = $systemAds->image_url;
            $notification->date_at = $systemAds->end_date;
            $notification->company_id = $systemAds->company_id;
            $notification->ad_id = $systemAds->id;
            $notification->time_at = $systemAds->not_time;
            $notification->save();
        }

    }

    /**
     * Handle the system_ ads "deleted" event.
     *
     * @param  \App\System_Ads  $systemAds
     * @return void
     */
    public function deleted(System_Ads $systemAds)
    {
        //
    }

    /**
     * Handle the system_ ads "restored" event.
     *
     * @param  \App\System_Ads  $systemAds
     * @return void
     */
    public function restored(System_Ads $systemAds)
    {
        //
    }

    /**
     * Handle the system_ ads "force deleted" event.
     *
     * @param  \App\System_Ads  $systemAds
     * @return void
     */
    public function forceDeleted(System_Ads $systemAds)
    {
        //
    }
}
