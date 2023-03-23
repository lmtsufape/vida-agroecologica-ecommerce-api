<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumidor extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->morphOne(User::class, "papel");
    }

    public function carrinho()
    {
        return $this->hasOne(Carrinho::class);
    }
}
