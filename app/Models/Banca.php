<?php

namespace App\Models;

use App\Contracts\FileableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banca extends Model implements FileableInterface
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'descricao',
        'horario_abertura',
        'horario_fechamento',
        'preco_minimo',
        'entrega',
        'feira_id',
        'agricultor_id',
        'pix'
    ];

    public function feira(): BelongsTo
    {
        return $this->belongsTo(Feira::class);
    }

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class);
    }

    public function agricultor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agricultor_id');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function formasPagamento(): BelongsToMany
    {
        return $this->belongsToMany(FormaPagamento::class)->withTimestamps();
    }

    public function vendas(): HasMany
    {
        return $this->hasMany(Venda::class);
    }

    public function bairros_info_entrega(): BelongsToMany
    {
        return $this->belongsToMany(Bairro::class)->withPivot('taxa')->withTimestamps();
    }
}
