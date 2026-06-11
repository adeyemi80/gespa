<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageParent extends Model
{
    use HasFactory;

    public function inscription()
{
    return $this->belongsTo(Inscription::class, 'inscription_id');
}
}
