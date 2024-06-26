<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\Tag;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Imports\ContactImporter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Actions\EditAction;

class OldContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Contactos';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('user_id')
                ->required()
                ->numeric()
                ->default(fn () => Auth::id()),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\Select::make('organization_id')
                ->label('Organization')
                ->options(Auth::user()->organizations->pluck('name', 'id'))
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $customFields = CustomField::where('organization_id', $state)->get();
                    $set('custom_fields', $customFields);
                })
                ->default(fn () => Auth::user()->organizations->count() === 1 ? Auth::user()->organizations->first()->id : null),
            Forms\Components\Card::make([
                Forms\Components\Repeater::make('custom_fields')
                    ->schema(function ($get) {
                        $organizationId = $get('organization_id') ?? null;
                        return CustomField::where('organization_id', $organizationId)->get()->map(function ($customField) {
                            return Forms\Components\TextInput::make('custom_' . $customField->id)
                                ->label($customField->name);
                        })->toArray();
                    })
                    ->label('Custom Fields')
            ])->columnSpan('full')->visible(fn ($get) => $get('organization_id')),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organization_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(ContactImporter::class)
                    /*->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Select File')
                            ->required(),
                    ])
                    ->after(function (array $data, Import $import) {
                        $importId = Str::uuid();
                        Contact::where('import_id', null)->update(['import_id' => $importId]);

                        return redirect()->route('contact-assign', ['importId' => $importId]);
                    }),
                    */
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
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'contact-assign' => Pages\ContactAssign::route('/assign/{importId}'),
        ];
    }
}