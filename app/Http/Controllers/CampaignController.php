<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index()
    {
        try {
            $campaigns = Campaign::where('user_id', Auth::id())->get();
            return response()->json($campaigns, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch campaigns'], 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $campaign = Campaign::create([
                'organization_id' => $request->organization_id,
                'list_id' => $request->list_id,
                'template_id' => $request->template_id,
                'variables' => $request->variables,
                'scheduled_at' => $request->scheduled_at,
                'send_now' => $request->send_now,
                'user_id' => Auth::id(),
            ]);

            return response()->json($campaign, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Campaign creation failed', 'message' => $e->getMessage()], 409);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            $campaign->update($request->all());

            return response()->json($campaign, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Campaign update failed'], 409);
        }
    }

    public function delete($id)
    {
        try {
            Campaign::findOrFail($id)->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Campaign deletion failed'], 409);
        }
    }

    public function getStatus($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            // Implementar lógica para obtener el estado de la campaña
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch campaign status'], 500);
        }
    }

    public function getDetails($id)
    {
        try {
            $campaign = Campaign::findOrFail($id);
            // Implementar lógica para obtener los detalles de la campaña
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not fetch campaign details'], 500);
        }
    }
}
