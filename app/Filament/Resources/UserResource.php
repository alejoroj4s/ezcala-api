<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Administrador';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Usuarios';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                ->required(fn (Forms\Components\TextInput $component) => $component->isVisible()),
            Forms\Components\Select::make('roles')
                ->relationship('roles', 'name')
                ->options(Role::all()->pluck('name', 'id'))
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('permissions', Role::find($state)?->permissions->pluck('id')->toArray() ?? []))
                ->required(),
            Forms\Components\CheckboxList::make('permissions')
                ->options(Permission::all()->pluck('name', 'id'))
                ->reactive()
                ->disabled(fn ($state) => true)
                ->helperText('Permisos asignados al rol seleccionado'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('roles.name')->label('Role')->sortable(),
            ])
            ->filters([
                SelectFilter::make('roles')->relationship('roles', 'name'),
                SelectFilter::make('permissions')->relationship('permissions', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return auth()->user()->can('manage users');
    // }
}
