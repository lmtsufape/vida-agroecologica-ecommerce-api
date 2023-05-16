<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forma_pagamento extends Model
{
    use HasFactory;

    protected $table = 'formas_pagamento';

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }
}
