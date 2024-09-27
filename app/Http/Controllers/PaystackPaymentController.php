<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Payment;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Auth;
use App\Mail\JobPosted;
use Illuminate\Support\Facades\Mail;
use App\Models\Job;
use App\Models\PaymentReceipt; 


class PaystackPaymentController extends Controller
{
    public function redirectToGateway(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $baseUrl = config('app.url');
            $callbackUrl = $baseUrl . '/payment/callback';

            $data = [
                'email' => $request->user()->email,
                'amount' => $request->amount * 100,
                'callback_url' => $callbackUrl,
            ];

            Payment::create([
                'amount' => $request->amount,
                'user_id' => $request->user()->id,
            ]);

            return Paystack::getAuthorizationUrl($data)->redirectNow();
        } catch (\Exception $e) {
            Log::error('Paystack redirect error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'The Paystack token has expired. Please try again.']);
        }
    }

    public function handleGatewayCallback(Request $request)
{
    $paymentDetails = Paystack::getPaymentData();

    try {
        $paymentReceipt = PaymentReceipt::create([
            'user_id' => $request->user()->id,
            'payment_reference' => $paymentDetails['data']['reference'] ?? null,
            'status' => $paymentDetails['data']['status'],
            'amount' => $paymentDetails['data']['amount'] / 100, 
            'paystack_response' => json_encode($paymentDetails),
            'error_message' => $paymentDetails['message'] ?? null,
            'description' => 'Payment for a featured job', 
            'payment_method' => $paymentDetails['data']['channel'] ?? null, 
           // 'payment_date' => $paymentDetails['data']['paid_at'] ?? now(),
            //'transaction_id' => $paymentDetails['data']['id'] ?? null,
     
        ]);
    } catch (\Exception $e) {
        Log::error('Failed to save payment receipt: ' . $e->getMessage());
        Log::info('Paystack payment details: ' . json_encode($paymentDetails));

        return redirect('/')->with('error', 'Failed to process payment data.');
    }

    if ($paymentDetails['status'] == 'success') {
        try {
            $userID = $request->user()->id;
            $reference = $paymentDetails['data']['reference'];
            $status = $paymentDetails['data']['status'];

            // Update payment record
            Payment::where('user_id', $userID)
                  ->where('payment_reference', $reference)
                  ->update([
                      'payment_status' => $status,
                  ]);

            // Retrieve job data from session
            $jobData = session('job_data');
            $tags = session('tags');

            // Ensure job data exists
            if (!$jobData) {
                Log::error('No job data found in session.');
                return redirect('/')->with('error', 'Job data not found.');
            }

            // Create the job
            $jobData['employer_id'] = Auth::user()->employer->id;
            $jobData['employer_name'] = Auth::user()->employer->name;
            $jobData['featured'] = true;
            unset($jobData['tags']);

            $job = Auth::user()->employer->jobs()->create($jobData);

            // Attach tags if any
            if (!empty($tags)) {
                foreach (explode(',', $tags) as $tag) {
                    $job->tag($tag);
                }
            }

            $user = Auth::user();
            try {
                Mail::to($user->email)->send(new JobPosted(
                    $job->title,
                    $job->salary,
                    $job->url,
                    $job->schedule,
                    $job->location,
                    $paymentReceipt->payment_reference,
                ));
                Log::info('Job posting email sent to ' . $user->email);
            } catch (\Exception $e) {
                Log::error('Failed to send job posting email: ' . $user->email . ' Error: ' . $e->getMessage());
                return redirect('/')->with('success', 'Payment successful and job posted, but email failed to send.');
            }

            // Clear session data
            session()->forget(['job_data', 'tags']);

            return redirect('/')->with('success', 'Payment successful and job posted!');
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Payment failed.');
        }
    } else {
        return redirect('/')->with('error', 'Payment not successful.');
    }
}

}
