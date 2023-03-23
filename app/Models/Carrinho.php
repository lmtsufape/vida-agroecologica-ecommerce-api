<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrinho extends Model
{
    use HasFactory;

    public function consumidor()
    {
        return $this->belongsTo(Consumidor::class);
    }
    public function sacolas()
    {
        return $this->hasMany(Sacola::class);
    }
}
