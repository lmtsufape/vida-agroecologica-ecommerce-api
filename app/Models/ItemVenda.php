<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemVenda extends Model
{
    use HasFactory;

    protected $table = 'itens_venda';

    protected $fillable = ['quantidade', 'preco', 'produto_id'];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class);
    }
}
