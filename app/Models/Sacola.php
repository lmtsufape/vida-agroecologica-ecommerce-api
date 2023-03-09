<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sacola extends Model
{
    use HasFactory;

    protected $fillable = ['total','loja_id'];

    public function carrinho()
    {
        return $this->belongsTo(Carrinho::class);
    }
    public function itens()
    {
        return $this->hasMany(ItemSacola::class);
    }
}
