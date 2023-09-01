<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\auth\CanResetPassword as reset;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, reset
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, SoftDeletes;

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

    public function enderecos()
    {
        return $this->morphMany(Endereco::class, 'addressable');
    }

    public function contato()
    {
        return $this->morphOne(Contato::class, 'contactable');
    }

    public function propriedades()
    {
        return $this->hasMany(Propriedade::class);
    }

    public function associacoesPresididas()
    {
        return $this->belongsToMany(Associacao::class, 'associacao_presidente', 'presidente_id', 'associacao_id')->withTimestamps();
    }

    public function associacao()
    {
        return $this->belongsTo(Associacao::class);
    }

    public function organizacao()
    {
        return $this->belongsTo(OrganizacaoControleSocial::class, 'organizacao_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasAnyRoles($roles)
    {
        return $this->roles()->whereIn('nome', $roles)->exists();
    }

    public function compras()
    {
        return $this->hasMany(Venda::class, 'consumidor_id');
    }

    public function vendas()
    {
        return $this->hasManyThrough(Venda::class, Banca::class, 'agricultor_id');
    }

    public function bancas()
    {
        return $this->hasMany(Banca::class, 'agricultor_id');
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
