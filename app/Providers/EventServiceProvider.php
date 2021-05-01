<?php

namespace App\Providers;

use App\Events\Registered;
use App\Listeners\InitUserRole;
use App\Listeners\StoreCommonUserDataInSession;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            InitUserRole::class,
        ],
        Login::class => [
            StoreCommonUserDataInSession::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
