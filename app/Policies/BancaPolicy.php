<?php

namespace App\Policies;

use App\Models\Banca;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BancaPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->papel_type == 'Produtor') {
            return null;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banca  $banca
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Banca $banca)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banca  $banca
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Banca $banca)
    {
        return $user->papel->banca->id === $banca->id
            ? Response::allow()
            : Response::deny('Esta banca não é sua.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banca  $banca
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Banca $banca)
    {
        return $user->papel->banca->id === $banca->id
            ? Response::allow()
            : Response::deny('Esta banca não é sua.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banca  $banca
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Banca $banca)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Banca  $banca
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Banca $banca)
    {
        //
    }
}
