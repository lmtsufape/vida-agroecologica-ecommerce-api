<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Venda;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class VendaPolicy
{
    use HandlesAuthorization;

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
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Venda $venda)
    {
        if ($user->papel_type == "Consumidor") {
            return $user->papel_id === $venda->consumidor_id
                ? Response::allow()
                : Response::deny();
        } else {
            return $user->papel_id === $venda->produtor_id
                ? Response::allow()
                : Response::deny();
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->papel_type == 'Consumidor'
            ? Response::allow()
            : Response::deny();
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
        //
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
        //
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
        //
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
        //
    }

    public function confirmarVenda(User $user, Venda $venda)
    {
        if ($user->papel_type == 'Produtor' && $user->papel_id === $venda->produtor_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    public function cancelarCompra(User $user, Venda $venda)
    {
        if ($user->papel_type == 'Consumidor' && $user->papel_id === $venda->consumidor_id) {
            return Response::allow();
        } elseif ($user->papel_type == 'Produtor' && $user->papel_id === $venda->produtor_id) {
            return Response::allow();
        }
        return Response::deny();
    }

    public function anexarComprovante(User $user, Venda $venda)
    {
        if ($user->papel_type == 'Consumidor' && $user->papel_id === $venda->consumidor_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    public function verComprovante(User $user, Venda $venda)
    {
        if ($user->papel_type == "Consumidor") {
            return $user->papel_id === $venda->consumidor_id
                ? Response::allow()
                : Response::deny();
        } else {
            return $user->papel_id === $venda->produtor_id
                ? Response::allow()
                : Response::deny();
        }
    }

    public function marcarEnviado(User $user, Venda $venda)
    {
        if ($user->papel_type == 'Produtor' && $user->papel_id === $venda->produtor_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    public function marcarEntregue(User $user, Venda $venda)
    {
        if ($user->papel_type == 'Consumidor' && $user->papel_id === $venda->consumidor_id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }
}
