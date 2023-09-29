<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimatequote extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Estimatepayment::class, 'estimate_id');
    }
    public function items()
    {
        return $this->hasMany(Item::class, 'estimate_id');
    }

    public function termsconditions()
    {
        return $this->hasMany(Estimatetermscondition::class, 'estimate_id');
    }

}
