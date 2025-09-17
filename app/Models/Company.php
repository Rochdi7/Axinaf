<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'sector',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Company has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Company belongs to many domains
    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'company_domain')
                    ->withTimestamps();
    }

    // Company QCM answers
    public function qcmAnswers()
    {
        return $this->hasMany(CompanyQcmAnswer::class);
    }

    // Access all checklists through domains
    public function checklists()
    {
        return $this->hasManyThrough(
            Checklist::class,
            Domain::class,
            'id',        // domains.id
            'domain_id', // checklists.domain_id
            'id',        // companies.id
            'id'         // domains.id
        )->whereHas('domains', function($q) {
            $q->where('domains.id', '=', 'company_domain.domain_id');
        });
    }
}
