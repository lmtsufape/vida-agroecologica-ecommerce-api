<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Contato extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'telefone'
    ];

    public function contactable(): MorphTo
    {
        return $this->MorphTo();
    }
}
