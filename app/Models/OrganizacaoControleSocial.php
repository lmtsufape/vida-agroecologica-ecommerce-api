<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizacaoControleSocial extends Model
{
    use HasFactory;

    protected $table = 'organizacoes_controle_social';

    protected $fillable = [
        "nome",
        "cnpj",
        "contato_id",
        "endereco_id",
        "associacao_id",
        "user_id"
    ];

    public function endereco()
    {
        return $this->morphOne(Endereco::class, 'addressable');
    }

    public function contato()
    {
        return $this->morphOne(Contato::class, 'contactable');
    }

    public function associacao()
    {
        return $this->belongsTo(Associacao::class, 'associacao_id');
    }

    public function agricultores()
    {
        return $this->hasMany(User::class, 'organizacao_id');
    }

    public function reunioes()
    {
        return $this->hasMany(Reuniao::class, 'organizacao_id');
    }
}
