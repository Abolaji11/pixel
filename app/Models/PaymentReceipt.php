<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReceipt extends Model
{
    protected $fillable = [
        'user_id',
        'payment_reference',
        'amount',
        'status',               
        'description',          
        'payment_method',               
        'paystack_response',    
        'error_message'       
    ];
    

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Job
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
   
}
