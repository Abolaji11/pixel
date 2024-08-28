<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Unicodeveloper\Paystack\Facades\Paystack;

// Your controller methods remain the same...



class PaystackPaymentController extends Controller
{
    public function redirectToGateway(Request $request)
    {
    
        try {
           //  Validate user and payment details
             $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            // Get the correct base URL based on the environment
            $baseUrl = config('app.url'); // or use env('APP_URL')

            // Create the callback URL dynamically
            $callbackUrl = $baseUrl . '/payment/callback';


                 
        
            $data = [
                'email' => $request->user()->email, // Ensure the email is set correctly
                'amount' => $request->amount * 100, // Amount in kobo
                'callback_url' => $callbackUrl, // Set the callback URL
            ];



            Payment::create([
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
            ]);
        
            
        
        
         return Paystack()->getAuthorizationUrl($data)->redirectNow();
         
            
        } catch (\Exception $e) {
            Log::error('Paystack redirect error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'The Paystack token has expired. Please try again.']);
        }

    }

    public function handleGatewayCallback(Request  $request)
    {
        $paymentDetails = Paystack::getPaymentData();

        //dd($paymentDetails);

        // Check the status of the payment
        if ($paymentDetails['status'] == 'success') {
            try {

                
             //   DB::beginTransaction();

              $user = $request->user()->id;

              $reference = $paymentDetails['data']['reference'];
              $status = $paymentDetails['data']['status'];
  

                Payment::where('user_id', $user)->first()->update([
                    'payment_reference' => $reference,
                    'payment_status' => $status,
                ]);

                // Save job as featured and handle post-payment logic here
                // Example: Save the job to the database
                // Job::create([...]);

               // DB::commit();

                return redirect('/')->with('success', 'Payment successful and job posted!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Post-payment logic error: ' . $e->getMessage());
                return redirect('/')->with('error', 'There was an error processing your payment. Please contact support.');
            }
        } else {
            return redirect('/')->with('error', 'Payment failed. Please try again.');
        }
    }
}
