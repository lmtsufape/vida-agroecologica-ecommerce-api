<?php

namespace App\Models;

use App\Contracts\ImageableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        return $this->imagem()->where('caminho', 'like', 'public/uploads/files/reuniao/anexos/%');
    }
    
    public function ata()
    {
        return $this->imagem()->where('caminho', 'like', 'public/uploads/files/reuniao/atas/%');
    }

    public function imagem(): MorphMany
    {
        return $this->morphMany(Imagem::class, 'imageable');
    }
}
