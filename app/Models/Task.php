<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function getCreated_atAttribute($value)
    {
       return $value->diffForHumans();
    }

    public function getUpdated_atAttribute($value)
    {
       return $value->diffForHumans();
    }
}
