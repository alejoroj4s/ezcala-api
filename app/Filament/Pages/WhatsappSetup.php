<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class WhatsappSetup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $view = 'filament.pages.whatsapp-setup';

    public function getAppId()
    {
        return env('APP_ID');
    }
}
