<?php

namespace App\Models;

use App\Contracts\ImageableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Venda extends Model implements ImageableInterface
{
    use HasFactory;

    protected $fillable = ['tipo_entrega'];

    public function banca(): BelongsTo
    {
        return $this->belongsTo(Banca::class);
    }

    public function formaPagamento(): BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function consumidor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consumidor_id');
    }

    public function enderecoEntrega(): MorphOne
    {
        return $this->morphOne(Endereco::class, 'addressable');
    }

    public function itens(): HasMany
    {
        return $this->hasMany(ItemVenda::class);
    }

    public function comprovante(): MorphOne
    {
        return $this->imagem();
    }

    public function imagem(): MorphOne
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }
}
