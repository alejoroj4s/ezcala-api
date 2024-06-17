<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhatsAppAccount;
use Illuminate\Support\Facades\Auth;

class WhatsAppAccountController extends Controller
{
    public function index()
    {
        try {
            $accounts = WhatsAppAccount::where('user_id', Auth::id())->get();
            return response()->json($accounts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch WhatsApp accounts'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $account = WhatsAppAccount::create([
                'user_id' => Auth::id(),
                'organization_id' => $request->organization_id,
                'whatsapp_number' => $request->whatsapp_number,
                'whatsapp_number_id' => $request->whatsapp_number_id,
                'account_id' => $request->account_id,
                'access_token' => $request->access_token,
            ]);

            return response()->json($account, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'WhatsApp account creation failed'], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $account = WhatsAppAccount::findOrFail($id);
            $account->update($request->all());

            return response()->json($account, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'WhatsApp account update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            WhatsAppAccount::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'WhatsApp account deletion failed'], 409);
        }
    }

    public function generateEmbedSignupLink()
    {
        // Implementar lógica para generar el enlace de registro de WhatsApp
    }
}
