<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Serveice extends Model
{
    use HasFactory;

    public function getImageAttribute($value)
    {
       if($value==''){
        return $value;
       }else{
        return env('PROD_URL').$value;
       }
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
