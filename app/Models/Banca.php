<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banca extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'horario_funcionamento',
        'horario_fechamento',
        'funcionamento',
        'preco_minimo',
        'tipo_entrega'
    ];

    protected $visible = [
        'id',
        'nome',
        'descricao',
        'horario_funcionamento',
        'horario_fechamento',
        'funcionamento',
        'preco_minimo',
        'tipo_entrega'
    ];
}
