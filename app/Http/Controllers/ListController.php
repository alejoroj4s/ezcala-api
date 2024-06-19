<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactList;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function index()
    {
        try {
            $lists = ContactList::where('user_id', Auth::id())->get();
            return response()->json($lists, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch lists'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $list = ContactList::create([
                'user_id' => Auth::id(),
                'organization_id' => $request->organization_id,
                'name' => $request->name,
            ]);

            return response()->json($list, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'List creation failed', 'message' => $e->getMessage()], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $list = ContactList::findOrFail($id);
            $list->update($request->all());

            return response()->json($list, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'List update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            ContactList::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'List deletion failed'], 409);
        }
    }
}
