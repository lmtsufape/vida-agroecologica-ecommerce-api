<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

interface ImageableInterface
{
    public function imagem(): MorphOneOrMany;
}
