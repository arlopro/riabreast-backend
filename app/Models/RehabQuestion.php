<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['rehab_period_id', 'question', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
        'labels' => 'array',
        'block_if' => 'array',
    ];


    public function period()
    {
        return $this->belongsTo(RehabPeriod::class, 'rehab_period_id');
    }
}
