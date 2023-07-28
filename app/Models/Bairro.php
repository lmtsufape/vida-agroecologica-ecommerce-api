<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'taxa'];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class);
    }

    public function bancas_info_entrega()
    {
        return $this->belongsToMany(Banca::class)->withPivot('taxa', 'faz_entrega')->withTimestamps();
    }
}
