<?php

namespace App\Models;

use App\Contracts\ImageableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Reuniao extends Model implements ImageableInterface
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
    
    public function ata(): MorphOne
    {
        return $this->imagem();
    }

    public function imagem(): MorphOne
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }
}
