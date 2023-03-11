<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'estoque',
        'preço',
        'tipo_unidade',
        'custo'
    ];

    public function banca()
    {
        return $this->belongsTo(Banca::class);
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class,'produto_categorias', 'produto_id', 'categoria_id');
    }
}
