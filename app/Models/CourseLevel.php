<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CourseLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'order',
        'text_contents',
        'images',
        'videos',
        'passing_score'
    ];

    protected $casts = [
        'text_contents' => 'array',
        'images' => 'array',
        'videos' => 'array'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'level_id');
    }
    // في app/Models/CourseLevel.php

public function addImage($path)
{
    $images = $this->images ?? [];
    $images[] = $path;
    $this->images = $images;
    $this->save();
}

public function addVideo($path)
{
    $videos = $this->videos ?? [];
    $videos[] = $path;
    $this->videos = $videos;
    $this->save();
}

public function addTextContent($content)
{
    $textContents = $this->text_contents ?? [];
    $textContents[] = $content;
    $this->text_contents = $textContents;
    $this->save();
}

public function removeContent($type, $index)
{
    $contents = $this->{$type} ?? [];
    
    if (isset($contents[$index])) {
        Storage::delete('public/' . $contents[$index]);
        unset($contents[$index]);
        $this->{$type} = array_values($contents); // إعادة ترتيب المفاتيح
        $this->save();
        return true;
    }
    
    return false;
}
}