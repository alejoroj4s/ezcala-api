<?php

namespace App\Policies;

use App\Models\CustomField;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CustomFieldPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('view custom-fields');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomField $customField): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('view custom-fields');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('create custom-fields');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomField $customField): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('edit custom-fields');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomField $customField): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('delete custom-fields');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomField $customField): bool
    {
        return $user->hasRole('super-admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomField $customField): bool
    {
        return $user->hasRole('super-admin');
    }
}
