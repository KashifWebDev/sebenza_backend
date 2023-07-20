<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mettingnote extends Model
{
    use HasFactory;

    public function mettings()
    {
        return $this->belongsTo(Meting::class, 'meting_id');
    }

}
