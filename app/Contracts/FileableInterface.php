<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;

interface FileableInterface
{
    public function file(): MorphOneOrMany;
}
