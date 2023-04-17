<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtor extends Model
{
    use HasFactory;

    protected $table = 'produtores';
    protected $fillable = ['name'];

    public function user()
    {
        return $this->morphOne(User::class, "papel");
    }

    public function banca()
    {
        return $this->hasOne(Banca::class);
    }
}
