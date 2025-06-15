<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUser extends Model
{
    use HasFactory;

    protected $table = 'coursesusers'; // The table name

    protected $fillable = ['course_id', 'user_id', 'apply', 'favorite'];

    public $timestamps = false; // Disable timestamps
}
