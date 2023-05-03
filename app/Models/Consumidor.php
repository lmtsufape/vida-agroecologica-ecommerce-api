<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumidor extends Model
{
    use HasFactory;

    protected $table = 'consumidores';

    public function user()
    {
        return $this->morphOne(User::class, "papel");
    }

    public function compras(): HasMany
    {
        return $this->hasMany(Venda::class);
    }
}
