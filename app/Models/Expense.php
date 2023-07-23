<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public function expensetypes()
    {
        return $this->belongsTo(Expensetype::class, 'expensetype_id');
    }

    public function getImageAttribute($value)
    {
       if($value==''){
        return $value;
       }else{
        return env('PROD_URL').$value;
       }
    }

}
