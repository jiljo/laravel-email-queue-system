<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailQueueController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', function () {
    return view('index');
});

Route::post('/send-email', [MailQueueController::class, 'send'])->name('send-email');

Route::get('/email-status', [MailQueueController::class, 'getStatuses'])->name('status');
Route::post('/retry-email', [MailQueueController::class, 'retry'])->name('retry');




