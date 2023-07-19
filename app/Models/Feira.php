<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feira extends Model
{
    use HasFactory;
    protected $fillable = [
        'funcionamento',
        'horario_abertura', 'horario_fechamento', 'bairro_id'
    ];
    protected $casts = [
        'funcionamento' => 'array'
    ];

    public function bairro()
    {
        return $this->belongsTo(Bairro::class);
    }
    public function bancas()
    {
        return $this->hasMany(Banca::class);
    }
}
