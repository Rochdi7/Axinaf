<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_qcm_answer_id',
        'action_text',
        'responsible_name', // Updated to use the text field name
        'deadline',
        'evaluation',
    ];

    public function qcmAnswer(): BelongsTo
    {
        return $this->belongsTo(UserQcmAnswer::class, 'user_qcm_answer_id');
    }

    /**
     * The responsible person is now stored as a string, so no Eloquent relationship is needed.
     */
}