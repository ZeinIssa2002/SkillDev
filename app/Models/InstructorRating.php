<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorRating extends Model
{
    protected $table = 'instructorratings'; // Set the correct table name
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;
    // Define the fillable fields
    protected $fillable = [
        'instructor_id',
        'user_id',
        'rating',
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }


}
