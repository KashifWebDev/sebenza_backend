<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stockitem extends Model
{
    use HasFactory;

    public function stocks()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
