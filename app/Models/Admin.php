<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin'; // اسم الجدول في قاعدة البيانات
    protected $fillable = ['account_id']; // الحقول القابلة للتعبئة

    // العلاقة مع جدول accounts
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}