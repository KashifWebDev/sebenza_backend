<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public function projects()
    {
        return $this->hasMany(Project::class, 'customer_id');
    }
    public function cases()
    {
        return $this->hasMany(Casemanagement::class, 'customer_id');
    }
}
