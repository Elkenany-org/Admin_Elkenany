<?php

namespace App\Observers;

use App\Configuration;
use App\NotificationSystem;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Http\Services\NotificationService;
use Modules\Store\Entities\Customer;

class NotificationObserver
{

    public $notificationService;

    /**
     * @param NotificationService $notificationService
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the noty "created" event.
     *
     * @param  \App\Noty  $noty
     * @return void
     */

    public function created(Notification $noty)
    {
        if($noty->status == 1){
            $this->notificationService->sendFcm($noty->title,$noty->body,$noty->image_thum_url);
        }
    }


    /**
     * Handle the noty "updated" event.
     *
     * @param  \App\Noty  $noty
     * @return void
     */
    public function updated(Notification $noty)
    {
        if($noty->status == 1){
            $this->notificationService->sendFcm($noty->title,$noty->body,$noty->image_thum_url);
        }
    }

    /**
     * Handle the noty "deleted" event.
     *
     * @param  \App\Noty  $noty
     * @return void
     */
    public function deleted(Notification $noty)
    {
        //
    }

    /**
     * Handle the noty "restored" event.
     *
     * @param  \App\Noty  $noty
     * @return void
     */
    public function restored(Notification $noty)
    {
        //
    }

    /**
     * Handle the noty "force deleted" event.
     *
     * @param  \App\Noty  $noty
     * @return void
     */
    public function forceDeleted(Notification $noty)
    {
        //
    }


}
