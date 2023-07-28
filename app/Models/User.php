<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\auth\CanResetPassword as reset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, reset
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'apelido',
        'cpf'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function endereco()
    {
        return $this->morphOne(Endereco::class, 'addressable');
    }

    public function contato()
    {
        return $this->belongsTo(Contato::class);
    }

    public function propriedades()
    {
        return $this->hasMany(Propriedade::class);
    }

    public function associacoes()
    {
        return $this->hasMany(Associacao::class);
    }

    public function organizacao()
    {
        return $this->belongsTo(OrganizacaoControleSocial::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasAnyRoles($roles)
    {
        return $this->roles()->whereIn('nome', $roles)->exists();
    }

    public function transacoes()
    {
        return $this->hasMany(Venda::class);
    }

    public function bancas()
    {
        return $this->hasMany(Banca::class);
    }
}
