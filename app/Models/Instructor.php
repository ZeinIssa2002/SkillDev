<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use App\Models\Account;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructor';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'rating',
        'profile_id',
        'account_id',
        'workhours',
    ];

    // Relationship with Profile
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }

    // Relationship with Account
    public function account()
    {
        return $this->hasOne(Account::class, 'instructor_id', 'id');
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
    
    // Relationship with Instructor Ratings
    public function ratings()
    {
        return $this->hasMany(InstructorRating::class);
    }

}
