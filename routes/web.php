<?php

use Eboseogbidi\Smartpaymentrouter\Http\Controllers\ProcessPaymentController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest','web'])->group(function(){
    Route::post('/handlePayment', [ProcessPaymentController::class, 'handlePayment'])->name('smartpaymentrouter.handlePayment');
    Route::get('/getProcessors', [ProcessPaymentController::class, 'testProcessManager'])->name('smartpaymentrouter.testProcessManager');
});