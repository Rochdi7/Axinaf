<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyQcmAnswer extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'domain_id',
        'question_id',
        'status',
        'updated_by',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id');
    }
}
