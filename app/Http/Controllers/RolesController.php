<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoleController extends Controller
{
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('app.api_base_url', 'http://localhost:8000');
    }

    /**
     * Display role management page (Admin only)
     */
    public function index()
    {
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        return view('admin.roles.index');
    }

    /**
     * Get roles data for admin
     */
    public function getRoles()
    {
        try {
            $response = Http::withToken(session('auth_token'))
                ->get($this->baseUrl . '/api/admin/roles');

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json(['error' => 'Failed to fetch roles'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $response = Http::withToken(session('auth_token'))
                ->post($this->baseUrl . '/api/admin/roles', $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to create role'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update role
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $response = Http::withToken(session('auth_token'))
                ->put($this->baseUrl . "/api/admin/roles/{$id}", $request->all());

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to update role'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete role
     */
    public function destroy($id)
    {
        try {
            $response = Http::withToken(session('auth_token'))
                ->delete($this->baseUrl . "/api/admin/roles/{$id}");

            if ($response->successful()) {
                return response()->json(['success' => true]);
            }

            return response()->json([
                'success' => false,
                'error' => $response->json()['error'] ?? 'Failed to delete role'
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}