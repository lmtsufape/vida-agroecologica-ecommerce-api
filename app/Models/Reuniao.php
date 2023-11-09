<?php

namespace App\Models;

use App\Contracts\FileableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Reuniao extends Model implements FileableInterface
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
        return $this->file()->where('path', 'like', 'public/uploads/files/reuniao/anexos/%');
    }
    
    public function ata()
    {
        return $this->file()->where('path', 'like', 'public/uploads/files/reuniao/atas/%');
    }

    public function file(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function participantes()
    {
        return $this->belongsToMany(User::class, 'reuniao_user', 'reuniao_id', 'participante_id')->withPivot('presenca')->withTimestamps();
    }
}
