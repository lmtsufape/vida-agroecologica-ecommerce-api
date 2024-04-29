<?php

namespace App\Models;

use App\Contracts\FileableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Enums\VendaStatusEnum;

class Venda extends Model implements FileableInterface
{
    use HasFactory;

    protected $fillable = ['tipo_entrega'];

    protected $enumCasts = [
        'status' => VendaStatusEnum::class,
    ];

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
        return $this->file();
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
