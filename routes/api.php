<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PaymentApiController;
use App\Http\Controllers\Api\WebhookController;

Route::post('/payments', [PaymentApiController::class, 'store']);
Route::post('/payments/webhook', [WebhookController::class, 'payment']);
