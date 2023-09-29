<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimatetermscondition extends Model
{
    use HasFactory;

    public function estimatequotes()
    {
        return $this->belongsTo(Estimatequote::class, 'estimate_id');
    }

    public function terms()
    {
        return $this->hasMany(Termscondition::class, 'termscondition_id');
    }

}
