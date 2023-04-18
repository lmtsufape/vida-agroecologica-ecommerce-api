<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Imagem extends Model
{
    use HasFactory;

    protected $table = 'imagens';

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }
}
