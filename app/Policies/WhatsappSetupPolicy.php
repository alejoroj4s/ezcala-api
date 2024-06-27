<?php

namespace App\Policies;

use App\Models\User;
use App\Filament\Pages\WhatsappSetup;
use Filament\Pages\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class WhatsappSetupPolicy
{

    use HandlesAuthorization;


    public function view(User $user): bool
    {
        return $user->hasRole('super-admin') || $user->hasPermissionTo('view whatsapp-setup');
    }
}
