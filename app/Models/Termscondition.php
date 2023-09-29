<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termscondition extends Model
{
    use HasFactory;

    public function estimatetermsconditions()
    {
        return $this->belongsTo(Estimatetermscondition::class, 'termscondition_id');
    }

}
