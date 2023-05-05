<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProdutoTabelado extends Model
{
    use HasFactory;

    protected $table = 'produtos_tabelados';

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'produto_tabelado_id');
    }

    public function imagem(): MorphOne
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }
}
