<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdutoTabelado extends Model
{
    use HasFactory;

    protected $table = 'produtos_tabelados';

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'produto_categorias', 'produto_tabelado_id', 'categoria_id');
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'produto_tabelado_id');
    }
}
