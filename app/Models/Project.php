<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    public function customers()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function projectexpense()
    {
        return $this->hasMany(Projectexpense::class, 'project_id');
    }

}
