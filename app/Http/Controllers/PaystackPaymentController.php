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

        if ($paymentDetails['status'] == 'success') {
            try {
                $userID = $request->user()->id;

                $reference = $paymentDetails['data']['reference'];
                $status = $paymentDetails['data']['status'];

                // Update payment record
                Payment::where('user_id', $userID)->first()->update([
                    'payment_reference' => $reference,
                    'payment_status' => $status,
                ]);

                // Retrieve job data from session
                
                $jobData = session('job_data');
                $tags = session('tags');

              //  dd($jobData);
                // Ensure job data exists
                if ($jobData) {
                    $jobData['employer_id'] = Auth::user()->employer->id;
                    $jobData['employer_name'] = Auth::user()->employer->name;
                    $jobData['featured'] = true;

                    unset($jobData['tags']);

                    // Create the job
                    $job = Auth::user()->employer->jobs()->create($jobData);

                    // Attach tags if any
                    if (!empty($tags)) {
                        foreach (explode(',', $tags) as $tag) {
                            $job->tag($tag);
                        }
                    }

                    // Send job posting email
                    $user = Auth::user();
                    try {
                        Mail::to($user->email)->send(new JobPosted(
                            $job->title,
                            $job->salary,
                            $job->url,
                            $job->schedule,
                            $job->location
                        ));
                        Log::info('Job posting email sent to ' . $user->email);
                    } catch (\Exception $e) {
                        Log::error('Failed to send job posting email: ' . $user->email . ' Error: ' . $e->getMessage());
                    }

                    // Clear session data
                    session()->forget(['job_data', 'tags']);

                    return redirect('/')->with('success', 'Payment successful and job posted!');
                } else {
                    Log::error('No job data found in session.');
                    return redirect('/')->with('error', 'Job data not found.');
                }
            } catch (\Exception $e) {
                Log::error('Payment processing error: ' . $e->getMessage());
                return redirect('/')->with('error', 'Payment failed.');
            }
        } else {
            return redirect('/')->with('error', 'Payment not successful.');
        }
    }
}
