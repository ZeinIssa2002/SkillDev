<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRating extends Model
{
    protected $table = 'courseratings'; // Set the correct table name
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['course_id', 'user_id', 'rating'];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }


}
