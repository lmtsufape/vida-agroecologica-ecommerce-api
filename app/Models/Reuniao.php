<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reuniao extends Model
{
    use HasFactory;

    protected $table = 'reunioes';

    protected $fillable = [
        'titulo',
        'detalhamento',
        'status',
        'data',
        'tipo',
        'associacao_id'
    ];

    public function associacao()
    {
        return $this->belongsTo(Associacao::class);
    }
}
