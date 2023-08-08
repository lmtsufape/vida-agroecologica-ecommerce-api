<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'longitude',
        'latitude',
        'estado_id'
    ];

    public function bairros()
    {
        return $this->hasMany(Bairro::class);
    }

    //pertence
}
