<?php

namespace App\Filament\Pages;

use App\Models\Organization;
use Filament\Pages\Page;
use Illuminate\Http\Request;
class Payment extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static string $view = 'filament.pages.payment';

    public function mount()
    {
        // Puedes agregar lógica inicial aquí si es necesario
    }

    public function createSubscription(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $organization = auth()->user()->organizations()->find($request->organization_id);

        if (!$organization) {
            return back()->withErrors(['organization_id' => 'Invalid organization selected.']);
        }

        try {
            $organization->newSubscription('default', 'price_id') // 'price_id' is the Stripe price ID
                ->create($request->payment_method);

            return redirect()->route('filament.pages.payment')->with('success', 'Subscription created successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create subscription: ' . $e->getMessage()]);
        }
    }
}