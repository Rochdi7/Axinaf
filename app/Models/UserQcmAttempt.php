<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserQcmAttempt extends Model
{
    protected $fillable = ['user_id', 'checklist_id', 'started_at', 'finished_at', 'score', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function answers()
    {
        return $this->hasMany(UserQcmAnswer::class);
    }
}
