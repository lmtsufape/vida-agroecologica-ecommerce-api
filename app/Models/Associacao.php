<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Associacao extends Model
{
    use HasFactory;

    protected $table = 'associacoes';

    protected $fillable = [
        'id',
        'nome',
        'codigo',
        'email',
        'telefone',
        'contato_id',
        'user_id'
    ];

    public function organizacoesControleSocial()
    {
        return $this->hasMany(OrganizacaoControleSocial::class);
    }

    public function contato()
    {
        return $this->morphOne(Contato::class, 'contactable');
    }

    public function presidentes()
    {
        return $this->belongsToMany(User::class, 'associacao_presidente', 'associacao_id', 'presidente_id')->withTimestamps();
    }

    public function agricultores()
    {
        return $this->hasMany(User::class);
    }

    public function feiras()
    {
        return $this->hasMany(Feira::class);
    }

    public function reunioes()
    {
        return $this->hasMany(Reuniao::class);
    }
}
