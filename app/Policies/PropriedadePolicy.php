<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Propriedade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropriedadePolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($ability == 'create') {
            return null;
        }

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
     * @param  \App\Models\Propriedade  $propriedade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Propriedade $propriedade)
    {
        if ($user->id === $propriedade->user_id) {
            return Response::allow();
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
        if (!$user->hasAnyRoles(['agricultor'])) {
            return Response::deny();
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Propriedade  $propriedade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Propriedade $propriedade)
    {
        if ($user->id === $propriedade->user_id) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Propriedade  $propriedade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Propriedade $propriedade)
    {
        if ($user->id === $propriedade->user_id) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Propriedade  $propriedade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Propriedade $propriedade)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Propriedade  $propriedade
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Propriedade $propriedade)
    {
        //
    }

    public function getPropriedades(User $user, User $agricultor)
    {
        if ($user->id === $agricultor->id) {
            return Response::allow();
        }

        return Response::deny();
    }
}
