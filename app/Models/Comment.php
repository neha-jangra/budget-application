<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id', 'project_id', 'parent_id'];

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function taggedUsers()
    {
        return $this->hasMany(CommentsTaggedUser::class);
    }

    public function attachments()
    {
        return $this->hasMany(CommentsAttachment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reads()
    {
        return $this->hasMany(CommentRead::class);
    }
}
