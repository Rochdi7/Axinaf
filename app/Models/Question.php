<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Allow mass assignment for these columns
    protected $fillable = [
        'checklist_id',       // required for $c->questions()->create()
        'sous_famille_id',    // optional
        'question_text',      // must match DB column name
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function sousFamille()
    {
        return $this->belongsTo(SousFamille::class);
    }
}
