<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Resources\Pages\CreateRecord;
use App\Traits\HandlesCustomFields;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;
}