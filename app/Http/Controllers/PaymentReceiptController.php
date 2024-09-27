<?php

namespace App\Http\Controllers;

use App\Models\PaymentReceipt;
use Illuminate\Support\Facades\Auth;


class PaymentReceiptController extends Controller
{
  
    public function index()
    {
        $receipts = PaymentReceipt::all();
       // dd($receipts);
        return view('receipts.index', compact('receipts'));
        


    }

   public function show($identifier)
{
    if (is_numeric($identifier)) {
        $receipt = PaymentReceipt::where('user_id', Auth::id())->findOrFail($identifier);
    } else {
        $receipt = PaymentReceipt::where('payment_reference', $identifier)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    return view('receipts.show', compact('receipt'));
}



}


