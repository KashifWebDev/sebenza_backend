<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function getReplayattAttribute($value)
    {
       if($value==''){
        return $value;
       }else{
        return env('PROD_URL').$value;
       }
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->diffForhumans() ;
    }

    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->diffForhumans() ;
    }


}