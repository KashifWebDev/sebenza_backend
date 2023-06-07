<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function getcreated_atAttribute($value)
    {
       return $value->diffForHumans();
    }

    public function getUpdatedAtAttribute($value)
    {
       return $value->diffForHumans();
    }
}
