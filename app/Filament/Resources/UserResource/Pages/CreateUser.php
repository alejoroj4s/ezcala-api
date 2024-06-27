<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;
use Livewire\Component;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $roles = $data['roles'];
        unset($data['roles']);
        
        $permissions = $data['permissions'];
        unset($data['permissions']);

        $user = User::create($data);
        $user->syncRoles($roles);
        $user->syncPermissions($permissions);

        return $data;
    }

    // public function mount(): void
    // {
    //     parent::mount();

    //     if (!Auth::user()->can('manage users')) {
    //         abort(403, 'No tienes permisos para acceder a este recurso');
    //         Notification::make()
    //         ->title('No tienes permisos para acceder a este recurso')
    //         ->failed()
    //         ->send();
    //         //return $this->previousUrl ?? $this->getResource()::getUrl('index');
    //     }
    // }
}

