<?php

namespace App\Jobs;

use App\Mail\NewSchedulingMail;
use App\Models\Scheduling;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSchedulingNotificationJob implements ShouldQueue
{
    use Queueable;

    public $scheduling;

    /**
     * Create a new job instance.
     */
    public function __construct(Scheduling $scheduling)
    {
        $this->scheduling = $scheduling;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $admins = User::whereHas('userType', function ($query) {
            $query->where('name', 'Admin');
        })->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewSchedulingMail($this->scheduling));
        }
    }
}
