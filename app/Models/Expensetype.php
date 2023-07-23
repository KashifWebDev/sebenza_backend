<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensetype extends Model
{
    use HasFactory;

    public function expense()
    {
        return $this->hasMany(Expense::class, 'expense_type_id');
    }


}