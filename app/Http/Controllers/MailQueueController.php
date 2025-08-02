<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailLog;           // Email logging model
use App\Jobs\SendEmailJobs;         // The queued job that sends email
use Illuminate\Support\Facades\View;

class MailQueueController extends Controller
{
    
     // Store email request and dispatch to queue
    
    public function send(Request $request)
    {
         $request->validate([
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string',
    ]);

    // Create log entry in DB with 'queued' status
    $emailLog = EmailLog::create([
        'email' => $request->email,
        'subject' => $request->subject,
        'message' => $request->message,
        'status' => 'queued',
    ]);

    // Dispatch job to queue
    dispatch(new SendEmailJobs($emailLog));

    return response()->json(['message' => 'Email queued successfully!']);
    }

    
     //Return queued, sent, failed emails for AJAX auto-refresh UI
     
    public function status()
    {
        $queued = EmailLog::where('status', 'queued')->latest()->get();
        $sent   = EmailLog::where('status', 'sent')->latest()->get();
        $failed = EmailLog::where('status', 'failed')->latest()->get();

        return response()->json([
            'queued' => View::make('emails.partials.queued', compact('queued'))->render(),
            'sent'   => View::make('emails.partials.sent', compact('sent'))->render(),
            'failed' => View::make('emails.partials.failed', compact('failed'))->render(),
        ]);
    }

    
     // Retry failed email job
     
    

    public function getStatuses()
{
    $queued = EmailLog::where('status', 'queued')->latest()->get();
    $sent = EmailLog::where('status', 'sent')->latest()->get();
    $failed = EmailLog::where('status', 'failed')->latest()->get();

    return response()->json([
        'queued' => view('partials.email_list', ['emails' => $queued, 'status' => 'queued'])->render(),
        'sent' => view('partials.email_list', ['emails' => $sent, 'status' => 'sent'])->render(),
        'failed' => view('partials.email_list', ['emails' => $failed, 'status' => 'failed'])->render(),
    ]);
}

public function retry(Request $request)
{
    $email = EmailLog::findOrFail($request->id);

    // Only retry failed jobs
    if ($email->status === 'failed') {
        dispatch(new SendEmailJobs($email));
        $email->status = 'queued';
        $email->save();
    }

    return response()->json(['success' => true]);
}




}
