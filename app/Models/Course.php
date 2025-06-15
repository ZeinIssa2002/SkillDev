<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'title',
        'coursepreview',
        'content', // النص الكبير
        'category_id',
        'instructor_id',
        'prerequisite_id',
        'photo', // Thumbnail
        'video', // الفيديو
        'placement_test',
        'placement_pass_score',
        'difficulty_level',
        'files', // JSON field for storing file information
    ];

    protected $casts = [
        'files' => 'array',
    ];

    protected $attributes = [
        'files' => '[]',
    ];

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define relationship with prerequisite course
    public function prerequisite()
    {
        return $this->belongsTo(Course::class, 'prerequisite_id');
    }

    // Define reverse relationship for courses that have this as prerequisite
    public function dependentCourses()
    {
        return $this->hasMany(Course::class, 'prerequisite_id');
    }

    // Handle file uploads
    public function uploadFile($file, $type = 'other')
    {
        $allowedTypes = ['rar', 'zip', 'pdf', 'docx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedTypes)) {
            throw new \Exception('نوع الملف غير مسموح به. الملفات المسموح بها: ' . implode(', ', $allowedTypes));
        }
        
        $path = $file->store('course_files/' . $this->id, 'public');
        
        $fileData = [
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'type' => $type,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'uploaded_at' => now()->toDateTimeString(),
        ];
        
        $files = $this->files ?? [];
        $files[] = $fileData;
        $this->files = $files;
        $this->save();
        
        return $fileData;
    }
    
    // Delete a file
    public function deleteFile($index)
    {
        $files = $this->files ?? [];
        
        if (isset($files[$index])) {
            // Delete the physical file
            if (\Storage::disk('public')->exists($files[$index]['path'])) {
                \Storage::disk('public')->delete($files[$index]['path']);
            }
            
            // Remove from array
            array_splice($files, $index, 1);
            $this->files = $files;
            $this->save();
            
            return true;
        }
        
        return false;
    }

    /////////////////////////////////
    // Relationship with Course Ratings
    public function ratings()
    {
        return $this->hasMany(CourseRating::class);
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'coursesusers')
                    ->withPivot('apply');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'course_id');
    }

    // علاقة مع الصور الإضافية
    public function images()
    {
        return $this->hasMany(CourseImage::class);
    }

    // علاقة مع المستويات
    public function levels()
    {
        return $this->hasMany(CourseLevel::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
    
    // Define the relationship with Instructor
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }
}
