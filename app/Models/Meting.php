<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meting extends Model
{
    use HasFactory;

    public function notes()
    {
        return $this->hasMany(Mettingnote::class, 'meting_id');
    }

}
