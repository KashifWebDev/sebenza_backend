<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimatepayment extends Model
{
    use HasFactory;

    public function estimatequotes()
    {
        return $this->belongsTo(Estimatequote::class, 'estimate_id');
    }

}
