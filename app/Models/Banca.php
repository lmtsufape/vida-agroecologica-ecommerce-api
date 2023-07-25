<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banca extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'horario_abertura',
        'horario_fechamento',
        'funcionamento',
        'preco_minimo',
        'faz_entrega',
        'feira_id'
    ];

    protected $visible = [
        'id',
        'nome',
        'descricao',
        'horario_abertura',
        'horario_fechamento',
        'funcionamento',
        'preco_minimo',
        'tipo_entrega',
        'feira_id'
    ];

    public function feira(){
        return $this->belongsTo(Feira::class);
    }
    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function produtor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function imagem(): MorphOne
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }

    public function formasPagamento(): BelongsToMany
    {
        return $this->belongsToMany(FormaPagamento::class, 'banca_forma_pagamento', 'banca_id', 'forma_pagamento_id');
    }

    public function bairros_info_entrega()
    {
        return $this->belongsToMany(Bairro::class);
    }
}
