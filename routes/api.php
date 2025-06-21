<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::prefix('midtrans')->group(function () {
  Route::post('webhook', [PaymentController::class, 'handleWebhook']);
});
