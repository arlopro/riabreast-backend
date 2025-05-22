<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RehabAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['rehab_session_id', 'rehab_question_id', 'answer'];

    public function session()
    {
        return $this->belongsTo(RehabSession::class, 'rehab_session_id');
    }

    public function question()
    {
        return $this->belongsTo(RehabQuestion::class, 'rehab_question_id');
    }
}
