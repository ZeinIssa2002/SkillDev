<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    // Define fillable attributes
    protected $fillable = ['account_id', 'rating'];
    public $timestamps = false;
    // The relationship with the Account table (change 'user_id' to 'account_id')
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
