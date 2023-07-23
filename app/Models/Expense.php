<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public function expenseypes()
    {
        return $this->belongsTo(Expensetype::class, 'expense_type_id');
    }

    public function getProfileAttribute($value)
    {
       if($value==''){
        return $value;
       }else{
        return env('PROD_URL').$value;
       }
    }

}