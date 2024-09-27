<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = []; 

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function employer(): HasOne
    {
        return $this->hasOne(Employer::class);
    }


    public function payment() {

        return $this->BelongsToMany(Payment::class);
    }

    public function paymentReceipt()
    {
        return $this->hasMany(paymentReceipt::class);
    }

    public function isAdmin()
    {
        return $this->is_admin===true;
    }
    

}
