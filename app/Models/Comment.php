<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comment';
    protected $primaryKey = 'id';

    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = ['course_id', 'account_id', 'comment'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }
    public function account()
{
    return $this->belongsTo(Account::class, 'account_id');
}

        /**
     * Replies relationship: A comment can have many replies.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Parent relationship: A reply belongs to a parent comment.
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}
