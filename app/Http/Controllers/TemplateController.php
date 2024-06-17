<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        try {
            $templates = Template::whereHas('organization.users', function ($query) {
                $query->where('users.id', Auth::id());
            })->get();
            return response()->json($templates, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch templates'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $template = Template::create([
                'organization_id' => $request->organization_id,
                'meta_id' => $request->meta_id,
                'name' => $request->name,
                'category' => $request->category,
                'language' => $request->language,
                'metadata' => $request->metadata,
                'status' => $request->status,
                'user_id' => Auth::id(), // Aquí usamos user_id en lugar de created_by
            ]);

            return response()->json($template, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Template creation failed', 'message' => $e->getMessage()], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $template = Template::findOrFail($id);
            $template->update($request->all());

            return response()->json($template, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Template update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            Template::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Template deletion failed'], 409);
        }
    }

    public function syncWithMeta(Request $request)
    {
        // Implementar lógica para sincronizar plantillas con Meta
    }
}
