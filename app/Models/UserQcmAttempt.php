<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQcmAttempt extends Model
{
    protected $fillable = ['user_id', 'company_id', 'domain_id', 'checklist_id', 'status', 'started_at', 'finished_at', 'score','total_countable_questions'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';


    public function answers()
    {
        return $this->hasMany(UserQcmAnswer::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
