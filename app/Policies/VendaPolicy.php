<?php

namespace App\Policies;

use App\Models\Banca;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VendaPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($ability === 'confirmarVenda') {
            return null;
        }

        if ($ability === 'cancelarCompra') {
            return null;
        }

        if ($ability === 'anexarComprovante') {
            return null;
        }

        if ($ability === 'marcarEnviado') {
            return null;
        }

        if ($ability === 'marcarEntregue') {
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
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Venda $venda)
    {
        if ($user->id === $venda->banca->agricultor_id || $user->id === $venda->consumidor_id) {
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
    public function create(User $user, Banca $banca)
    {
        if ($user->hasAnyRoles(['consumidor'])) {
            if ($banca->agricultor->ativo) {
                return Response::allow();
            }
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Venda $venda)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Venda $venda)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Venda $venda)
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Venda $venda)
    {
        return Response::deny();
    }

    public function confirmarVenda(User $user, Venda $venda)
    {
        if ($user->id === $venda->banca->agricultor_id && $user->hasAnyRoles(['agricultor'])) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function cancelarCompra(?User $user, Venda $venda)
    {
        if (!$user) {
            return Response::allow();
        }

        if ($user->id === $venda->banca->agricultor_id || $user->id === $venda->consumidor_id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function anexarComprovante(User $user, Venda $venda)
    {
        return $user->id === $venda->consumidor_id
            ? Response::allow()
            : Response::deny();
    }

    public function verComprovante(User $user, Venda $venda)
    {
        if ($user->id === $venda->banca->agricultor_id || $user->id === $venda->consumidor_id) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function marcarEnviado(User $user, Venda $venda)
    {
        return $user->id === $venda->banca->agricultor_id
            ? Response::allow()
            : Response::deny();
    }

    public function marcarEntregue(User $user, Venda $venda)
    {
        return $user->id === $venda->consumidor_id
            ? Response::allow()
            : Response::deny();
    }

    public function getTransacoes(User $user, User $model)
    {
        return $user->id === $model->id
            ? Response::allow()
            : Response::deny();
    }
}
