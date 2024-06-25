<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use App\Models\ContactList;
use App\Models\Tag;
use App\Models\Contact;

class ContactAssign extends Page
{
    protected static string $resource = ContactResource::class;

    protected static string $view = 'filament.resources.contact-resource.pages.contact-assign';

    public $importId;

    public function mount($importId)
    {
        $this->importId = $importId;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('list_id')
                ->label('List')
                ->options(ContactList::all()->pluck('name', 'id'))
                ->required(),
            Forms\Components\MultiSelect::make('tags')
                ->label('Tags')
                ->options(Tag::all()->pluck('name', 'id')),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        Contact::where('import_id', $this->importId)->update(['list_id' => $data['list_id']]);
        if (!empty($data['tags'])) {
            foreach (Contact::where('import_id', $this->importId)->get() as $contact) {
                $contact->tags()->sync($data['tags']);
            }
        }

        $this->notify('success', 'Contacts have been successfully assigned.');
        return redirect()->route('filament.resources.contacts.index');
    }
}