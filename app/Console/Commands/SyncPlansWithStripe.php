<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Plan;
use Stripe\Stripe;
use Stripe\Price;

class SyncPlansWithStripe extends Command
{
    protected $signature = 'sync:plans';
    protected $description = 'Synchronize plans with Stripe';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripePlans = Price::all();

        foreach ($stripePlans->data as $stripePlan) {
            Plan::updateOrCreate(
                ['stripe_plan_id' => $stripePlan->id],
                [
                    'name' => $stripePlan->nickname,
                    'price' => $stripePlan->unit_amount / 100,
                    'description' => $stripePlan->product->description,
                ]
            );
        }

        $this->info('Plans synchronized successfully.');
    }
}
