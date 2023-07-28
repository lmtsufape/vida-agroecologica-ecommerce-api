<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizacaoControleSocial extends Model
{
    use HasFactory;

    protected $table = 'organizacoes_controle_social';

    protected $fillable = [
        'nome',
        'representante',
        "cnpj",
        "data_fundacao",
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
        return $this->belongsTo(Contato::class);
    }

    public function associacao()
    {
        return $this->belongsTo(Associacao::class);
    }

    public function agricultor()
    {
        return $this->hasMany(User::class);
    }
}
