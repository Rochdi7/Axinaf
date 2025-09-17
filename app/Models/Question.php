<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'checklist_id',
        'sous_famille_id',
        'question_text',
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function sousFamille()
    {
        return $this->belongsTo(SousFamille::class);
    }

    // QCM answers by companies
    public function companyAnswers()
    {
        return $this->hasMany(CompanyQcmAnswer::class);
    }
    public function userAnswers()
    {
        return $this->hasMany(UserQcmAnswer::class, 'question_id');
    }
}
