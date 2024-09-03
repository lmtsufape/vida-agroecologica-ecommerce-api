<?php

namespace App\Policies;

use App\Models\Endereco;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($ability === 'create') {
            return null;
        }

        if ($ability === 'delete') {
            return null;
        }

        if ($ability === 'forceDelete') {
            return null;
        }

        if ($ability === 'createEndereco') {
            return null;
        }

        if ($ability === 'updateUserRoles') {
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
        return Response::allow();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        return !$model->hasAnyRoles(['administrador']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(?User $user, $roles_id)
    {
        $allRoles = Role::all();
        $roles = [];

        foreach ($roles_id as $role_id) {
            array_push($roles, $allRoles->find($role_id)->nome);
        }

        if (in_array('administrador', $roles)) {
            return Response::deny();
        } elseif (in_array('presidente', $roles) || in_array('secretario', $roles)) {
            if (!$user || !$user->hasAnyRoles(['administrador'])) {
                return Response::deny();
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        if ($user->hasAnyRoles(['administrador', 'presidente'])) {
            return Response::allow();
        }
        
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {
        if ($model->hasAnyRoles(['administrador'])) {
            return Response::deny();
        }

        if ($user->hasAnyRoles(['administrador'])) {
            return Response::allow();
        }

        return $user->id === $model->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        if ($model->hasAnyRoles(['administrador'])) {
            return Response::deny();
        }

        if ($user->hasAnyRoles(['administrador'])) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function updateUserRoles(User $user, User $model, $roles_id)
    {
        if ($model->hasAnyRoles(['administrador'])) {
            return Response::deny('A role administrador não pode ser modificada');
        }

        if (!$user->hasAnyRoles(['administrador', 'presidente'])) {
            return Response::deny();
        }

        $allRoles = Role::all();
        $roles = [];

        foreach ($roles_id as $role_id) {
            array_push($roles, $allRoles->find($role_id)->nome);
        }

        if (!in_array('agricultor', $roles)) {
            if ($model->organizacao()->exists() || $model->associacao()->exists()) {
                return Response::deny();
            }
        }

        if (!in_array('presidente', $roles)) {
            if ($model->associacoesPresididas()->exists()) {
                return Response::deny();
            }
        }

        if (in_array('administrador', $roles)) {
            return Response::deny();
        }

        return Response::allow();
    }
}
