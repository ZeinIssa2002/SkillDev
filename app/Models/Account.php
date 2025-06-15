<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Profile;
use App\Models\User;
use App\Models\Instructor;


class Account extends Authenticatable
{

    use HasFactory, Notifiable;

    protected $table = 'account';
    public $incrementing = true;
    protected $keyType = 'int';


    protected $fillable = [
        'username',
        'email',
        'password',
        'account_type',
        'user_id',         // Foreign key to User
        'instructor_id',   // Foreign key to Instructor
    ];

    protected $hidden = [
        'password',
    ];

    // Relationship with Profile
    public function profile()
    {
        return $this->hasOne(Profile::class, 'account_id', 'id');
    }

    // Conditional relationship with User based on account_type
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->where('account_type', 'user');
    }

    // Conditional relationship with Instructor based on account_type
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id')->where('account_type', 'instructor');
    }

    // Custom method to get either User or Instructor based on account_type
    public function associatedProfile()
    {
        if ($this->account_type === 'user') {
            return $this->user;
        } elseif ($this->account_type === 'instructor') {
            return $this->instructor;
        }

        return null;
    }

    // العلاقة مع جدول admins
    public function admin()
    {
        return $this->hasOne(Admin::class, 'account_id');
    }

    // دالة للتحقق مما إذا كان الحساب مشرفًا
    public function isAdmin()
    {
        return $this->admin()->exists();
    }

    public function sentFriendRequests()
{
    return $this->hasMany(FriendRequest::class, 'sender_id');
}

public function receivedFriendRequests()
{
    return $this->hasMany(FriendRequest::class, 'receiver_id');
}

public function sentMessages()
{
    return $this->hasMany(Message::class, 'sender_id');
}

public function receivedMessages()
{
    return $this->hasMany(Message::class, 'receiver_id');
}
}
