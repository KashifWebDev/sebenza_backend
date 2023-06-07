<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function getSubjectAttribute($value)
    {
       return 'testsssssss'.$value;
    }

    public function getUpdatedAtAttribute($value)
    {
       return 'test'.$value->diffForHumans();
    }
}
