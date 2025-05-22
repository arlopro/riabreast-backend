<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabExtra extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'video_youtube_id'];
}
