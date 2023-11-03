<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    use HasFactory;

    protected $table = 'reunioes';

    protected $fillable = [
        'titulo',
        'pauta',
        'data',
        'tipo',
        'associacao_id',
        'organizacao_id'
    ];

    public function associacao()
    {
        return $this->belongsTo(Associacao::class, 'associacao_id');
    }

    public function organizacao()
    {
        return $this->belongsTo(OrganizacaoControleSocial::class, 'organizacao_id');
    }

    public function anexos()
    {
        return $this->morphMany(Imagem::class, 'imageable');
    }
}
