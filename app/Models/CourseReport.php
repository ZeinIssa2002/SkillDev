<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReport extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'course_id',
        'reporter_id',
        'reason',
        'details',
        'in_progress',
        'resolved'
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