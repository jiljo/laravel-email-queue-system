<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function testSend()
    {
        Mail::raw('This is a test message', function ($message) {
            $message->to('j4jiljokg@gmail.com') // Replace with your email
                    ->subject('Test Subject');
        });

        return "✅ Email sent (if SMTP is working correctly)";
    }
}
