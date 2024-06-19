<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;

class OrganizationController extends Controller
{
    public function getAllOrganizations()
    {
        try {
            $organizations = Organization::all();
            return response()->json($organizations, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch organizations'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $organization = Organization::create([
                'name' => $request->name,
            ]);

            return response()->json($organization, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Organization creation failed'], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $organization = Organization::findOrFail($id);
            $organization->update($request->all());

            return response()->json($organization, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Organization update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            Organization::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Organization deletion failed'], 409);
        }
    }
}
