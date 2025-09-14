<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = ['title', 'description', 'created_by', 'is_active'];

    public function familles()
    {
        return $this->hasMany(Famille::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function attempts()
    {
        return $this->hasMany(UserQcmAttempt::class);
    }
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
