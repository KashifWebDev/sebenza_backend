<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function getAttachmentAttribute($value)
    {
       return env('PROD_URL').$value;
    }

    public function getCreatedAtAttribute($value)
    {
        $date = strtotime($value);
        return $date;
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = strtotime($value);
        return $date;
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

}
