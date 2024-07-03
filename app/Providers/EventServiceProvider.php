<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\SendInvoiceEmailEvent;
use App\Listeners\SendInvoiceEmailListener;
use App\Events\CreateJobEmailEvent;
use App\Listeners\CreateJobEmailListener;
use App\Events\ScheduleJobEmailEvent;
use App\Listeners\ScheduleJobEmailListener;
use App\Events\AssignJobEmailEvent;
use App\Listeners\AssignJobEmailListener;
use App\Events\CompleteJobEmailEvent;
use App\Listeners\CompleteJobEmailListener;
use App\Events\LoginInfoEmailEvent;
use App\Listeners\LoginInfoEmailListener;
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
        SendInvoiceEmailEvent::class => [
            SendInvoiceEmailListener::class,
        ],
        CreateJobEmailEvent::class=> [
            CreateJobEmailListener::class,
        ],
        ScheduleJobEmailEvent::class=> [
            ScheduleJobEmailListener::class,
        ],
        AssignJobEmailEvent::class=> [
            AssignJobEmailListener::class,
        ],
        CompleteJobEmailEvent::class=> [
            CompleteJobEmailListener::class,
        ],
        LoginInfoEmailEvent::class=> [
            LoginInfoEmailListener::class,
        ]
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
