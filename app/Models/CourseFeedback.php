<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'reporter_id',
        'type',
        'details',
        'resolved',
        'in_progress'
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'in_progress' => 'boolean'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function reporter()
    {
        return $this->belongsTo(Account::class, 'reporter_id');
    }
}