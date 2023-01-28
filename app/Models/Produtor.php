<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtor extends Model
{
    use HasFactory;

    protected $fillable = ['banco_id','banca_id','distancia_feira','distancia_semana'];

    public function user()
    {
        return $this->morphOne(User::class, "papel");
    }
}
