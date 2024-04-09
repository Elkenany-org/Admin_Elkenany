<?php

namespace App\Listeners;

use App\Events\MagazinCreate;
use App\Noty;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Magazines\Entities\Magazine;
use Modules\Notification\Http\Services\NotificationService;

class PushNotificationAfterCreate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        $service = new NotificationService;
        // $service->sendNotificastion($event);
    }
}
