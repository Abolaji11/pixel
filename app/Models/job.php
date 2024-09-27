<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class job extends Model
{
    use HasFactory;
    
   protected $guided = [];
    public function tag(string $name)
    {
       $tag= Tag::firstOrCreate(['name' => $name]);
       $this->tags()->attach($tag);
    }

    
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    
    public function employer(): BelongsTo
    {
        return $this->belongsTo(employer::class);
    }

    public function paymentReceipt()
    {
        return $this->hasOne(paymentReceipt::class);
    }



}
