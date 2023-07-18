<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = ['rua', 'cep', 'numero', 'complemento', 'cidade', 'estado', 'pais', 'bairro', 'bairro_id'];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function propriedade()
    {
        return $this->hasOne(Propriedade::class);
    }

    public function organizacaoControleSocial()
    {
        return $this->hasOne(OrganizacaoControleSocial::class);
    }
}
