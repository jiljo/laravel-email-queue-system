<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    // Allow mass assignment on these fields
    protected $fillable = [
        'email', 'subject', 'message', 'status'
    ];
}

