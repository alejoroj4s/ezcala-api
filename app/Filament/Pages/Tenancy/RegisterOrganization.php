<?php

namespace App\Filament\Pages\Tenancy;
 
use App\Models\Organization;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
 
class RegisterOrganization extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Registrar Organización';
    }
 
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                // ...
            ]);
    }
 
    protected function handleRegistration(array $data): Organization
    {
        $organization = Organization::create($data);
 
        $organization->users()->attach(auth()->user());
 
        return $organization;
    }
}