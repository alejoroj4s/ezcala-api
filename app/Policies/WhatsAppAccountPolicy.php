<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WhatsAppAccount;
use Illuminate\Auth\Access\Response;

class WhatsAppAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('view whatsapp-accounts');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WhatsAppAccount $whatsAppAccount): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('view whatsapp-accounts');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('create whatsapp-accounts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WhatsAppAccount $whatsAppAccount): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('edit whatsapp-accounts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WhatsAppAccount $whatsAppAccount): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('delete whatsapp-accounts');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WhatsAppAccount $whatsAppAccount): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WhatsAppAccount $whatsAppAccount): bool
    {
        return $user->hasRole('super-admin');
    }
}
