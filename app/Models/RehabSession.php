<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabSession extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rehab_period_id', 'started_at', 'completed_at', 'is_completed'];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(RehabPeriod::class, 'rehab_period_id');
    }

    public function answers()
    {
        return $this->hasMany(RehabAnswer::class);
    }
}
