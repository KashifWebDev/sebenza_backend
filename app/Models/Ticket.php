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
       return diffForHumans(strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
       return diffForHumans(strtotime($value));
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

}
