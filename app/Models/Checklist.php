<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $fillable = [
        'domain_id',
        'title',
        'description',
        'created_by',
        'is_active'
    ];

    /**
     * Checklist belongs to a domain.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    /**
     * Checklist has many familles.
     */
    public function familles()
    {
        return $this->hasMany(Famille::class);
    }

    /**
     * Checklist created by a user.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Checklist has many attempts (for QCM).
     */
    public function attempts()
    {
        return $this->hasMany(UserQcmAttempt::class);
    }

    /**
     * Checklist has many questions (direct relation if needed).
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
