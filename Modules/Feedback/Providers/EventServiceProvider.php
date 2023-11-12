<?php

namespace Modules\Feedback\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Feedback\Events\RemarkRegistered;

//Listeners
Use Modules\Feedback\Listeners\NotifyRemark;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RemarkRegistered::class => [
            NotifyRemark::class,
        ],
    ];
}
