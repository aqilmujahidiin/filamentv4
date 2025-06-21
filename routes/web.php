<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {
  Route::get('/payment/{payment}/pay', [PaymentController::class, 'pay'])->name('payment.pay');
});
