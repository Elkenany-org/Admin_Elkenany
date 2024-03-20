<?php

namespace App\Providers;

use App\NotificationSystem;
use App\Observers\AdsObserver;
use App\Observers\NotificationObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Modules\Notification\Entities\Notification;
use Modules\SystemAds\Entities\System_Ads;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination.bootstrap-4');
        Notification::observe(NotificationObserver::class);
        System_Ads::observe(AdsObserver::class);
    }
}
