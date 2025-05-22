<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'video_youtube_id'];

    public function questions()
    {
        return $this->hasMany(RehabQuestion::class);
    }

    public function sessions()
    {
        return $this->hasMany(RehabSession::class);
    }
}
