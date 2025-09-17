<?php

// app/Models/UserQcmAnswer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserQcmAnswer extends Model
{
    protected $fillable = [
        'user_qcm_attempt_id',
        'question_id',
        'status',
    ];

    /**
     * Get the QCM attempt that the answer belongs to.
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(UserQcmAttempt::class, 'user_qcm_attempt_id');
    }

    /**
     * Get the question that the answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the action plan associated with the user QCM answer.
     */
    public function actionPlan(): HasOne
    {
        return $this->hasOne(ActionPlan::class);
    }
}