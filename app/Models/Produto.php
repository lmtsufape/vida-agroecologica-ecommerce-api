<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'descricao',
        'tipo_unidade',
        'estoque',
        'preco',
        'custo',
        'disponivel',
        'banca_id',
        'produto_tabelado_id'
    ];

    public function banca()
    {
        return $this->belongsTo(Banca::class);
    }

    public function produtoTabelado(): BelongsTo
    {
        return $this->belongsTo(ProdutoTabelado::class, 'produto_tabelado_id');
    }

    public function itens_venda(): HasMany
    {
        return $this->hasMany(ItemVenda::class);
    }
}
