<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class EditRole extends EditRecord
{

    protected static string $resource = RoleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $role = $record;
        $role->update($data);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role;
    }

    protected function getSuccessNotificationMessage(): ?string
    {
        return 'Role updated successfully!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
