<?php

namespace App\Models;

use App\Contracts\ImageableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Feira extends Model implements ImageableInterface
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'descricao',
        'horarios_funcionamento',
        'bairro_id',
        'associacao_id'
    ];
    protected $casts = [
        'horarios_funcionamento' => 'array'
    ];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function bancas()
    {
        return $this->hasMany(Banca::class);
    }

    public function associacao()
    {
        return $this->belongsTo(Associacao::class);
    }

    public function imagem(): MorphOne
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }
}
