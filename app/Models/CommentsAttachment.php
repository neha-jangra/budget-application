<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['comment_id', 'file_path', 'file_type'];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
