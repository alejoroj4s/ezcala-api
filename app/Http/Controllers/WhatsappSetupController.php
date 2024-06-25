<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WhatsappSetupController extends Controller
{
    public function handleRedirect(Request $request)
    {
        // Aquí puedes manejar la respuesta de WhatsApp después de la autenticación
        // Procesa la información que se recibe después de la autenticación
        return response()->json(['message' => 'WhatsApp authenticated successfully']);
    }
}