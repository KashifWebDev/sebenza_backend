<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function complains()
    {
        return $this->hasMany(Complain::class, 'department_id');
    }
    public function suggestions()
    {
        return $this->hasMany(Suggestion::class, 'department_id');
    }
}
