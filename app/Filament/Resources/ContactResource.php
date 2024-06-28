<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\Tag;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use App\Filament\Imports\ContactImporter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Actions\ImportAction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Actions\CreateAction;
use Filament\Pages\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Enums\FiltersLayout;


class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-phone';

    protected static ?string $navigationGroup = 'CRM';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Contactos';

    protected static ?string $tenantRelationshipName = 'contacts';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('user_id')
                ->required()
                ->numeric()
                ->default(fn () => Auth::id())
                ->disabled(),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('phone')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            // Forms\Components\Select::make('organization_id')
            //     ->label('Organization')
            //     ->options(Auth::user()->organizations->pluck('name', 'id'))
            //     ->required()
            //     ->reactive()
            //     ->default(fn () => Auth::user()->organizations->count() === 1 ? Auth::user()->organizations->first()->id : null)
            //     ->disabled(fn () => Auth::user()->organizations->count() === 1),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('organization.name')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                
                BadgeColumn::make('lists')
                    ->label('Lists')
                    ->getStateUsing(function (Contact $record) {
                        return $record->lists->pluck('name')->toArray();
                    })
                    ->colors([
                        'primary',
                        'secondary',
                        'success',
                        'danger',
                        'warning',
                        'info',
                    ]),
                BadgeColumn::make('tags')
                    ->label('Tags')
                    ->getStateUsing(function (Contact $record) {
                        return $record->tags->pluck('name')->toArray();
                    })
                    ->colors([
                        'primary',
                        'secondary',
                        'success',
                        'danger',
                        'warning',
                        'info',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            /*->filters([
                SelectFilter::make('organization_id')
                    ->label('Organization')
                    ->options(fn() => Auth::user()->organizations->pluck('name', 'id'))
                    ->placeholder('All Organizations')
                    ->query(function ($query, $state) {
                        if ($state !== null) {
                            $query->where('organization_id', $state);
                        }
                    }),
                SelectFilter::make('lists')
                    ->label('Lists')
                    ->options(ContactList::all()->pluck('name', 'id'))
                    ->query(function ($query, $state) {
                        if ($state !== null) {
                            $query->whereHas('lists', function ($q) use ($state) {
                                $q->where('lists.id', $state);
                            });
                        }
                    })
                    ->placeholder('All Lists'),
                SelectFilter::make('tags')
                    ->label('Tags')
                    ->options(Tag::all()->pluck('name', 'id'))
                    ->query(function ($query, $state) {
                        if ($state !== null) {
                            $query->whereHas('tags', function ($q) use ($state) {
                                $q->where('tags.id', $state);
                            });
                        }
                    })
                    ->placeholder('All Tags'),
                Filter::make('created_at')
                    ->label('Creation Date')
                    ->form([
                        Forms\Components\DatePicker::make('created_at')
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['created_at'])) {
                            $query->whereDate('created_at', '=', $data['created_at']);
                        }
                    }),
                Filter::make('created_at_hour')
                    ->label('Creation Hour')
                    ->form([
                        Forms\Components\TimePicker::make('created_at_hour')
                            ->label('Creation Hour'),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['created_at_hour'])) {
                            $query->whereTime('created_at', '=', $data['created_at_hour']);
                        }
                    })
                    ,
                SelectFilter::make('user_id')
                    ->label('User ID')
                    ->options(Contact::distinct()->pluck('user_id', 'user_id'))
                    ->placeholder('All Users')
                    ->query(function ($query, $state) {
                        if ($state !== null) {
                            $query->where('user_id', $state);
                        }
                    }),
            ], layout: FiltersLayout::Modal)
            */



            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                
                    Tables\Actions\BulkAction::make('assignToList')
                    ->label('Assign to List')
                    ->form([
                        Select::make('list_id')
                            ->label('Select List')
                            ->options(ContactList::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data, $records) {
                        foreach ($records as $record) {
                            $record->lists()->attach($data['list_id']);
                        }
                    }),
                
                
                    Tables\Actions\BulkAction::make('assignToTag')
                    ->label('Assign to Tag')
                    ->form([
                        MultiSelect::make('tag_id')
                            ->label('Select Tags')
                            ->options(Tag::all()->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data, $records) {
                        foreach ($records as $record) {
                            $record->tags()->attach($data['tag_id']);
                        }
                    }),


                    Tables\Actions\BulkAction::make('assignCustomFields')
                    ->label('Assign Custom Fields')
                    ->form([
                        Select::make('organization_id')
                            ->label('Select Organization')
                            ->options(Auth::user()->organizations->pluck('name', 'id'))
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $customFields = CustomField::where('organization_id', $state)->get()->map(function ($field) {
                                    return [
                                        'id' => $field->id,
                                        'name' => $field->name,
                                    ];
                                })->toArray();
                                $set('custom_fields', $customFields);
                            }),
                        Forms\Components\TextInput::make('custom_fields')
                            ->label('Custom Fields')
                            ->schema(function ($get) {
                                $customFields = $get('custom_fields') ?? [];
                                return collect($customFields)->map(function ($customField) {
                                    return TextInput::make('custom_' . $customField['id'])
                                        ->label($customField['name']);
                                })->toArray();
                            }),
                    ])
                    ->action(function (array $data, $records) {
                        foreach ($records as $record) {
                            foreach ($data as $key => $value) {
                                if (strpos($key, 'custom_') === 0) {
                                    $customFieldId = explode('_', $key)[1];
                                    CustomFieldValue::updateOrCreate(
                                        ['contact_id' => $record->id, 'custom_field_id' => $customFieldId],
                                        ['value' => $value]
                                    );
                                }
                            }
                        }
                    })

            ])
            ->headerActions([
                //ImportAction::make()
                    //->importer(ContactImporter::class),
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
            //'contact-assign' => Pages\ContactAssign::route('/assign/{importId}'),
        ];
    }
}