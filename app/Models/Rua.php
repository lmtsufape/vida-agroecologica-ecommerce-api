<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rua extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'numero', 'complemento'];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function cep()
    {
        return $this->belongsTo(Cep::class);
    }
}
