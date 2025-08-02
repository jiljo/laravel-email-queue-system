<?php

namespace App\Jobs;

use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Throwable;

class SendEmailJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $emailLog; // Stores the email log record

    // Create a new job instance.
    public function __construct(EmailLog $emailLog)
    {
        $this->emailLog = $emailLog;
    }

   //  Execute the job.
    public function handle(): void
    {
        // Attempt to send the email
        Mail::raw($this->emailLog->message, function ($message) {
            $message->to($this->emailLog->email)
                    ->subject($this->emailLog->subject);
        });

        // Update status to 'sent'
        $this->emailLog->update(['status' => 'sent']);
    }

    // If job fails, handle failure
    public function failed(Throwable $exception): void
    {
        // Update status to 'failed' in DB
        $this->emailLog->update(['status' => 'failed']);
    }
}
