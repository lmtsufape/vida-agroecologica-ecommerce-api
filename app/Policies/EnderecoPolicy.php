<?php

namespace App\Policies;

use App\Models\Venda;
use App\Models\Endereco;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EnderecoPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasAnyRoles(['administrador'])) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Endereco $endereco)
    {
        if ($endereco->addressable_type === 'user' && $endereco->addressable_id === $user->id) {
            return Response::allow();
        } elseif ($endereco->addressable_type === 'venda') {
            $venda = Venda::find($endereco->addressable_id);
            if ($user->id === $venda->consumidor_id || $user->id === $venda->banca->agricultor_id) {
                return Response::allow();
            }
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Endereco $endereco)
    {
        if ($endereco->addressable_type === 'user' && $endereco->addressable_id === $user->id) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Endereco $endereco)
    {
        if ($endereco->addressable_type === 'user' && $endereco->addressable_id === $user->id && $user->enderecos->count() > 1) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Endereco $endereco)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Endereco  $endereco
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Endereco $endereco)
    {
        //
    }
}
