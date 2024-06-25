<?
// app/Traits/FilamentResourceTrait.php
namespace App\Traits;

use Filament\Forms;
use Illuminate\Support\Facades\Auth;

trait FilamentResourceTrait
{
    public static function form(Forms\Form $form)
    {
        return $form
            ->schema([
                // Other fields...

                Forms\Components\Hidden::make('user_id')
                ->default(fn() => auth()->id()),

                Forms\Components\Select::make('organization_id')
                ->label('Organization')
                ->options(fn() => auth()->user()->organizations->pluck('name', 'id'))
                ->required()
                ->visible(fn() => auth()->user()->organizations->count() > 1)
                ->default(fn() => auth()->user()->organizations->count() === 1 ? auth()->user()->organizations->first()->id : null),

                Forms\Components\Hidden::make('organization_id')
                ->default(fn() => auth()->user()->organizations->count() === 1 ? auth()->user()->organizations->first()->id : null),
                
            ]);
    }
}