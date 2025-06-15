<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Profile;
use App\Models\Account;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'account_id',
    ];

    // Relationship with Profile
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'profile_id');
    }

    // Relationship with Account
    public function account()
    {
        return $this->hasOne(Account::class, 'user_id', 'id');
    }
/////////////////
public function courses()
{
    return $this->belongsToMany(Course::class, 'coursesusers')
                ->withPivot('apply');

}


}
