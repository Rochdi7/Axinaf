<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // A domain has many checklists
    public function checklists()
    {
        return $this->hasMany(Checklist::class);
    }

    // Domain belongs to many companies
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_domain')
                    ->withTimestamps();
    }
}
