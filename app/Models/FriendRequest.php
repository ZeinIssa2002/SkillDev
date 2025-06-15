<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    // تعريف الجدول الذي يرتبط به الموديل (إذا كان الاسم غير الافتراضي)
    protected $table = 'friend_requests';

    // الأعمدة التي يمكن ملؤها (Mass Assignment)
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status'
    ];

    // العلاقة مع موديل Account (المستخدم الذي أرسل الطلب)
    public function sender()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    // العلاقة مع موديل Account (المستخدم الذي استلم الطلب)
    public function receiver()
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }
}
