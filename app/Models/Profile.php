<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\User;
use App\Models\Instructor;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profile';
    protected $primaryKey = 'profile_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'profilename',
        'profileinfo',
        'photo',
        'account_id',
    ];

    // Relationship with Account (belongsTo)
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    // Relationship with User (One-to-One, if applicable)
    public function user()
    {
        return $this->hasOne(User::class, 'profile_id', 'profile_id');
    }

    // Relationship with Instructor (One-to-One, if applicable)
    public function instructor()
    {
        return $this->hasOne(Instructor::class, 'profile_id', 'profile_id');
    }
}
