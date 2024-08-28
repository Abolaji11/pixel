<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function payment(request $request){
    $amount = $request->query('amount');
    $description = $request->query('description');

    return view('payment-page', compact('amount', 'description'));
    }
}



