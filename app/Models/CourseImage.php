<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseImage extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'course_id', // معرف الدورة المرتبطة بالصورة
        'image_path', // مسار الصورة
    ];

    /**
     * العلاقة بين الصورة والدورة.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // علاقة مع الدورة
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}