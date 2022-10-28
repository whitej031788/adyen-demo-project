<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineItem extends Model
{
    public function registrant()
    {
        return $this->belongsTo(Registrant::class);
    }
}
