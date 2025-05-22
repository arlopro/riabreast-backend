<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabUserProgress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'rehab_period_id', 'is_active', 'started_at', 'completed_at'];

    protected $casts = [
        'is_active' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rehabPeriod()
    {
        return $this->belongsTo(RehabPeriod::class, 'rehab_period_id');
    }
}
