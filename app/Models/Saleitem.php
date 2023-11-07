<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saleitem extends Model
{
    use HasFactory;

    public function sales()
    {
        return $this->hasMany(Sale::class, 'sale_id');
    }

}
