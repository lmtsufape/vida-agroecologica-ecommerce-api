<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feira extends Model
{
    use HasFactory;
    protected $fillable = [
        'funcionamento', 'latitude', 'longitude',
        'horario_abertura', 'horario_fechamento'
    ];
    protected $casts = [
        'funcionamento' => 'array'
    ];
}
