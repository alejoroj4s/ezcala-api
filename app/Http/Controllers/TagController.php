<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        try {
            $tags = Tag::where('user_id', Auth::id())->get();
            return response()->json($tags, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch tags'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $tag = Tag::create([
                'user_id' => Auth::id(),
                'organization_id' => $request->organization_id,
                'name' => $request->name,
            ]);

            return response()->json($tag, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tag creation failed'], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->update($request->all());

            return response()->json($tag, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tag update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            Tag::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Tag deletion failed'], 409);
        }
    }
}
