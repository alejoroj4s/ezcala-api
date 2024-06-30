<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;


class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('name')->required(),
            Select::make('organization_id')
                ->relationship('organization', 'name')
                ->required(),
            TextInput::make('stripe_id')->required(),
            TextInput::make('stripe_status')->required(),
            TextInput::make('stripe_price'),
            TextInput::make('quantity'),
            DateTimePicker::make('trial_ends_at'),
            DateTimePicker::make('ends_at'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('organization.name')->sortable()->searchable(),
                TextColumn::make('stripe_status')->sortable()->searchable(),
                TextColumn::make('stripe_price')->sortable()->searchable(),
                TextColumn::make('quantity')->sortable()->searchable(),
                TextColumn::make('trial_ends_at')->sortable()->searchable(),
                TextColumn::make('ends_at')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}