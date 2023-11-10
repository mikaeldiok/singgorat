<?php

namespace Modules\Reporting\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

//Events
Use Modules\Reporting\Events\ReportRegistered;

//Listeners
Use Modules\Reporting\Listeners\NotifyReport;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ReportRegistered::class => [
            NotifyReport::class,
        ],
    ];
}
