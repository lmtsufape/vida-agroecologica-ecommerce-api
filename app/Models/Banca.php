<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'horario_funcionamento',
        'horario_fechamento',
        'funcionamento',
        'preco_minimo',
        'tipo_entrega'
    ];
}
