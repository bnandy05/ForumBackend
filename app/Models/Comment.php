<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    protected $fillable = [
        'content',
        'topic_id',
        'user_id',
        'upvotes',
        'downvotes',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function votes()
    {
        return $this->hasMany(CommentVote::class);
    }
}