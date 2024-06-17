<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index()
    {
        try {
            $contacts = Contact::where('user_id', Auth::id())->get();
            return response()->json($contacts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch contacts'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $contact = Contact::create([
                'user_id' => Auth::id(),
                'organization_id' => $request->organization_id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return response()->json($contact, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact creation failed'], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $contact = Contact::findOrFail($id);
            $contact->update($request->all());

            return response()->json($contact, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            Contact::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Contact deletion failed'], 409);
        }
    }

    public function importFromCSV(Request $request)
    {
        // Implementar lógica para importar contactos desde CSV
    }
}
