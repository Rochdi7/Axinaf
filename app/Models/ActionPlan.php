<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_action_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_qcm_answer_id',
        'action_text',
        'responsible_name', // Updated to the new column name
        'deadline',
        'evaluation',
    ];

    /**
     * Get the user QCM answer that the action plan belongs to.
     */
    public function userQcmAnswer(): BelongsTo
    {
        return $this->belongsTo(UserQcmAnswer::class);
    }
    
    // The responsibleUser relationship is no longer needed since the column is a string.
}