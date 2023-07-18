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
        'latitude'
    ];
    public function bairro(){
        return this->hasMany(Bairro::class);
    }
}
