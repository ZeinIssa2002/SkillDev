<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'message', 'is_read'];
    
    /**
     * تشفير الرسالة قبل حفظها في قاعدة البيانات
     *
     * @param string $value
     * @return void
     */
    public function setMessageAttribute($value)
    {
        // تشفير جميع أنواع الرسائل بما في ذلك الصور والملفات
        $this->attributes['message'] = Crypt::encryptString($value);
    }
    
    /**
     * فك تشفير الرسالة عند استرجاعها من قاعدة البيانات
     *
     * @param string $value
     * @return string
     */
    public function getMessageAttribute($value)
    {
        try {
            // محاولة فك تشفير الرسالة
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // إذا فشل فك التشفير (مثلاً للرسائل القديمة غير المشفرة)، إرجاع القيمة كما هي
            return $value;
        }
    }

    public function sender()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }
}
