<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Famille extends Model
{
    protected $fillable = ['checklist_id', 'title', 'description'];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function sousFamilles()
    {
        return $this->hasMany(SousFamille::class);
    }
}

