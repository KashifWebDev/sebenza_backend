<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function stockitems()
    {
        return $this->hasMany(Stockitem::class, 'stock_id');
    }

    public function stockpayment()
    {
        return $this->hasMany(Stockpayment::class, 'stock_id');
    }

}
