<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feira extends Model
{
    use HasFactory;
    protected $fillable = [
        'horarios_funcionamento',
        'bairro_id'
    ];
    protected $casts = [
        'horarios_funcionamento' => 'array'
    ];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }

    public function bancas()
    {
        return $this->hasMany(Banca::class);
    }

    public function associacao()
    {
        return $this->belongsTo(Associacao::class);
    }

    public function imagem()
    {
        return $this->morphOne(Imagem::class, 'imageable');
    }
}
