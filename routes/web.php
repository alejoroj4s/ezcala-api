<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\WhatsAppAccountController;
use App\Http\Controllers\CampaignController;

Route::prefix('chat')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::get('user/token', [UserController::class, 'getToken']);

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [UserController::class, 'me']);
        Route::post('logout', [UserController::class, 'logout']);
        Route::post('refresh', [UserController::class, 'refresh']);

        // Contacts
        Route::get('contacts', [ContactController::class, 'index']);
        Route::post('contacts', [ContactController::class, 'create']);
        Route::put('contacts/{id}', [ContactController::class, 'update']);
        Route::delete('contacts/{id}', [ContactController::class, 'delete']);
        
        // Organizations
        Route::get('organizations', [OrganizationController::class, 'getAllOrganizations']);
        Route::post('organizations', [OrganizationController::class, 'create']);
        Route::put('organizations/{id}', [OrganizationController::class, 'update']);
        Route::delete('organizations/{id}', [OrganizationController::class, 'delete']);
        
        // Lists
        Route::get('lists', [ListController::class, 'index']);
        Route::post('lists', [ListController::class, 'create']);
        Route::put('lists/{id}', [ListController::class, 'update']);
        Route::delete('lists/{id}', [ListController::class, 'delete']);
        
        // Tags
        Route::get('tags', [TagController::class, 'index']);
        Route::post('tags', [TagController::class, 'create']);
        Route::put('tags/{id}', [TagController::class, 'update']);
        Route::delete('tags/{id}', [TagController::class, 'delete']);
        
        // Templates
        Route::get('templates', [TemplateController::class, 'index']);
        Route::post('templates', [TemplateController::class, 'create']);
        Route::put('templates/{id}', [TemplateController::class, 'update']);
        Route::delete('templates/{id}', [TemplateController::class, 'delete']);
        
        // WhatsApp Accounts
        Route::get('whatsapp-accounts', [WhatsAppAccountController::class, 'index']);
        Route::post('whatsapp-accounts', [WhatsAppAccountController::class, 'create']);
        Route::put('whatsapp-accounts/{id}', [WhatsAppAccountController::class, 'update']);
        Route::delete('whatsapp-accounts/{id}', [WhatsAppAccountController::class, 'delete']);
        
        // Campaigns
        Route::get('campaigns', [CampaignController::class, 'index']);
        Route::post('campaigns', [CampaignController::class, 'create']);
        Route::put('campaigns/{id}', [CampaignController::class, 'update']);
        Route::delete('campaigns/{id}', [CampaignController::class, 'delete']);
        Route::get('campaigns/status/{id}', [CampaignController::class, 'getStatus']);
        Route::get('campaigns/details/{id}', [CampaignController::class, 'getDetails']);
    });
});

use App\Http\Controllers\WhatsAppController;

Route::post('/whatsapp/callback', [WhatsAppController::class, 'callback']);

// use App\Http\Controllers\SubscriptionController;

// Route::post('/subscriptions/create', [SubscriptionController::class, 'create'])->name('subscriptions.create');
// Route::post('/subscriptions/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');