<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('email');      // recipient email
            $table->string('subject');    // email subject
            $table->text('message');      // email body
            $table->enum('status', ['queued', 'sent', 'failed'])->default('queued');
            $table->timestamps();         // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};


