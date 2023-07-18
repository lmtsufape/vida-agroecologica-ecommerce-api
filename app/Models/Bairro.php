<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bairro extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'taxa', 'cidade_id'];

    public function endereco()
    {
        return $this->hasMany(Endereco::class);
    }
    public function feira(){
        return $this->hasOne(Feira::class);
    }
    public function cidade(){
        return $this->belongsTo(Cidade::class);
    }
}
