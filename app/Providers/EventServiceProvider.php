<?php

namespace App\Providers;

use App\Events\CompanyCreate;
use App\Events\CompanyProductCreate;
use App\Events\LocalStockChanges;
use App\Events\MagazinCreate;
use App\Events\NewsCreate;
use App\Events\ShowCreate;
use App\Listeners\PushNotificationAfterCreate;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        MagazinCreate::class => [
            PushNotificationAfterCreate::class,
        ],
        NewsCreate::class => [
            PushNotificationAfterCreate::class,
        ],
        CompanyProductCreate::class => [
            PushNotificationAfterCreate::class,
        ],
//        LocalStockChanges::class => [
//            PushNotificationAfterCreate::class,
//        ],
        ShowCreate::class => [
            PushNotificationAfterCreate::class,
        ],
        CompanyCreate::class => [
            PushNotificationAfterCreate::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
