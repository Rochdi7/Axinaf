<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQcmAnswer extends Model
{
    protected $fillable = ['user_qcm_attempt_id', 'question_id', 'answer'];

    public function attempt()
    {
        return $this->belongsTo(UserQcmAttempt::class, 'user_qcm_attempt_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
