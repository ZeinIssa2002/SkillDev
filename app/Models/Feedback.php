<?php
// app/Models/Feedback.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';

    protected $fillable = ['account_id', 'feedbackinfo'];
    public $timestamps = false;
    // Relationship with the Account model
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

}
