<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdutoTabelado extends Model
{
    use HasFactory;

    protected $table = 'produtos_tabelados';

    public function produto(): HasMany
    {
        return $this->hasMany(Produto::class, 'produto_tabelado_id');
    }
}