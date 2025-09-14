<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SousFamille extends Model
{
    protected $fillable = ['famille_id', 'title', 'description'];

    public function famille()
    {
        return $this->belongsTo(Famille::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}

