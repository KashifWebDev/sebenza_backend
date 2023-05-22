<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seennewsupdate extends Model
{
    use HasFactory;

    public function newsupdates()
    {
        return $this->belongsTo(Newsupdate::class, 'news_id');
    }

}