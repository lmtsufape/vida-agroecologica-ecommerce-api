<?php

namespace App\Policies;

use App\Models\Banca;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProdutoPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
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
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Produto $produto)
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user, Banca $banca)
    {
        return $user->id === $banca->agricultor_id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Produto $produto)
    {
        return $user->id === $produto->banca->agricultor_id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Produto $produto)
    {
        return $user->id === $produto->banca->agricultor_id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Produto $produto)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Produto  $produto
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Produto $produto)
    {
        //
    }
}
