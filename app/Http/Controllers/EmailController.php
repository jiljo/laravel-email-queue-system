<?php

namespace App\Http\Controllers;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;

class EmailController extends Controller
{
   public function send(Request $request)
    {
        $userEmail = $request->input('email');

        // Dispatch the job to the queue
        SendEmailJob::dispatch($userEmail);

        return response()->json(['message' => 'Email job has been queued!']);
    }
}
