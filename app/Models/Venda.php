<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = ['status', 'tipo_entrega', 'data_pedido', 'total', 'comprovante_pagamento'];

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function produtor(): BelongsTo
    {
        return $this->belongsTo(Produtor::class);
    }

    public function consumidor(): BelongsTo
    {
        return $this->belongsTo(Consumidor::class);
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemVenda::class);
    }
}
