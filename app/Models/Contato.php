<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'telefone'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function organizacaoControleSocial()
    {
        return $this->hasOne(OrganizacaoControleSocial::class);
    }

    public function associacao()
    {
        return $this->hasOne(Associacao::class);
    }
}
