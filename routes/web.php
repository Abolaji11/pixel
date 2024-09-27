<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\jobcontroller;
use App\Http\Controllers\registerusercontroller;
use App\Http\Controllers\sessioncontroller;
use App\Http\Controllers\searchcontroller;
use App\Http\Controllers\tagcontroller;
use App\Http\Controllers\paystackpaymentcontroller;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentReceiptController;



Route::get('/career', [jobcontroller::class, 'career']);
Route::get('/', [jobcontroller::class, 'index']);
Route::get('/salary', [jobcontroller::class, 'salary']);

Route::get('/jobs/create', [jobcontroller::class,'create'])->middleware('auth');
Route::post('/jobs', [jobcontroller::class,'store'])->middleware('auth');

Route::post('/pay', [PaystackPaymentController::class, 'redirectToGateway'])->name('pay');
Route::get('/payment/callback', [PaystackPaymentController::class, 'handleGatewayCallback']);
Route::get('/payment-page',  [PaymentController::class, 'payment'])->name('payment-page');


Route::get('receipts/{id}', [PaymentReceiptController::class, 'show'])->name('receipts.show');
Route::get('receipts/{reference}', [PaymentReceiptController::class, 'show'])->name('receipts.show');
Route::middleware([  'web', 'auth', 'isAdmin'])->group(function () {
    Route::get('/payment-receipts', [PaymentReceiptController::class, 'index'])->name('receipts.index') ;
});



Route::get('/search', [searchcontroller::class, '__invoke']);
Route::get('/tags/{tag:name}', tagcontroller::class, );

//Route::middleware('guest')->group(function () {
    Route::get('/register', [registerusercontroller::class, 'create'])->middleware('guest');
    Route::post('/register', [registerusercontroller::class, 'store'])->middleware('guest');
    Route::get('/login', [sessioncontroller::class, 'create'])->middleware('guest');
    Route::post('/login', [sessioncontroller::class, 'store'])->middleware('guest');
//});

Route::delete('/logout', [sessioncontroller::class, 'destroy'])->middleware('auth');

