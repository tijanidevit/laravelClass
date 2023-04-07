<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function commentable () {
        return $this->morphTo();
    }
}

Post::create([

]);


$post->comment([
    'hello'
]);

// comment => hello
// commentable_type => post
// commentable_id => 1




Video::create([

]);


$video->comment([
    'hello'
]);

// comment => hello
// commentable_type => App\Models\Video
// commentable_id => 1
