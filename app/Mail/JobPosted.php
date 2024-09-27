<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class JobPosted extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $salary;
    public $location;
    public $url;
    public $schedule;
    public $paymentReference;

    /**
     * Create a new message instance.
     */
    public function __construct($title, $salary, $url, $schedule, $location, $paymentReference)
    {
        $this->title = $title;
        $this->salary = $salary;
        $this->location = $location;
        $this->url = $url;
        $this->schedule = $schedule;
        $this->paymentReference = $paymentReference;
        
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $receiptUrl = route('receipts.show', ['reference' => $this->paymentReference]);
        return $this->subject('Your Job Posting is Live!')
                    ->view('mail.JobPosted')
                    ->with([
                        'title' => $this->title,
                        'salary' => $this->salary,
                        'location' => $this->location,
                        'url' => $this->url,
                        'schedule' => $this->schedule,
                        'receiptUrl' => $receiptUrl,
             
                    ]);
    }
}

