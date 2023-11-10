<?php

namespace Modules\Reporting\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Reporting\Emails\NotifyRegistration;
use Mail;

use Modules\Reporting\Entities\Report;

class SendRegMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
   
    protected $details;
    protected $report;
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details,Report $report)
    {
        $this->details = $details;
        $this->report = $report;
    }
   
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new NotifyRegistration($this->report);
        Mail::to($this->details['email'])->send($email);
    }
}
