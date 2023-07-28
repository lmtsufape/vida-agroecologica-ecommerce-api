<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormaPagamento extends Model
{
    use HasFactory;

    protected $table = 'formas_pagamento';

    protected $fillable = ['tipo'];

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function bancas(): BelongsToMany
    {
        return $this->belongsToMany(Banca::class)->withTimestamps();
    }
}
