<?php

namespace App\Filament\Pages;

use App\Policies\WhatsappSetupPolicy;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;


class WhatsappSetup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string $view = 'filament.pages.whatsapp-setup';

    public function getAppId()
    {
        return env('APP_ID');
    }

    public function mount()
    {
        if (!Gate::allows('view', WhatsappSetup::class)) {
            abort(403);
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('view', WhatsappSetup::class);
    }
}
