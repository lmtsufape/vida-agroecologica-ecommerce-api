<?php

namespace App\Models;

use App\Contracts\FileableInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ProdutoTabelado extends Model implements FileableInterface
{
    use HasFactory;

    protected $table = 'produtos_tabelados';

    public function produtos(): HasMany
    {
        return $this->hasMany(Produto::class, 'produto_tabelado_id');
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
