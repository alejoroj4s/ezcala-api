<?php

/*

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/


/*
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
*/