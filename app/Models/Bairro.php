<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'taxa'];

    public function cep()
    {
        return $this->hasMany(Endereco::class);
    }
}
