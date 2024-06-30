<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Laravel\Cashier\SubscriptionBuilder;

class SubscriptionController extends Controller
{
    public function create(Request $request)
    {
        $organization = Organization::findOrFail($request->organization_id);
        $paymentMethod = $request->payment_method;

        $organization->newSubscription('default', 'price_id') // 'price_id' is the Stripe price ID
            ->create($paymentMethod);

        return response()->json(['message' => 'Subscription created successfully.']);
    }

    public function cancel(Request $request)
    {
        $organization = Organization::findOrFail($request->organization_id);

        $organization->subscription('default')->cancel();

        return response()->json(['message' => 'Subscription cancelled successfully.']);
    }
}