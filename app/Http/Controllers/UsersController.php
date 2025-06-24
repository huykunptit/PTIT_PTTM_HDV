<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getProfile(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Check authorization - users can only view their own profile unless admin
        if ($currentUser->id != $userId && $currentUser->role_id != 1) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = User::with(['account', 'role'])->find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    public function updateProfile(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Check authorization - users can only update their own profile unless admin
        if ($currentUser->id != $userId && $currentUser->role_id != 1) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'sometimes|required|string|max:255',
            'bod' => 'sometimes|required|date',
            'address' => 'sometimes|required|string|max:500',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $userId,
            'phone' => 'sometimes|required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            $user->update($request->only([
                'full_name', 'bod', 'address', 'email', 'phone'
            ]));

            $user->load(['account', 'role']);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update failed'], 500);
        }
    }

    public function deleteUser(Request $request, $userId)
    {
        $currentUser = Auth::user();
        
        // Only admin can delete users
        if ($currentUser->role_id != 1) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Don't allow admin to delete themselves
        if ($user->id == $currentUser->id) {
            return response()->json(['error' => 'Cannot delete your own account'], 400);
        }

        try {
            // Delete related account first
            if ($user->account) {
                $user->account->delete();
            }
            
            $user->delete();

            return response()->json(['message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed'], 500);
        }
    }

    public function listUsers(Request $request)
    {
        $currentUser = Auth::user();
        
        // Only admin can list all users
        if ($currentUser->role_id != 1) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $query = User::with(['account', 'role']);

        // Apply filters
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('account', function ($accountQuery) use ($search) {
                      $accountQuery->where('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('role_id')) {
            $query->where('role_id', $request->get('role_id'));
        }

        if ($request->has('created_from')) {
            $query->where('created_at', '>=', $request->get('created_from'));
        }

        if ($request->has('created_to')) {
            $query->where('created_at', '<=', $request->get('created_to'));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        
        $total = $query->count();
        $users = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        
        $totalPages = ceil($total / $perPage);

        return response()->json([
            'total' => $total,
            'users' => $users,
            'page' => (int) $page,
            'per_page' => (int) $perPage,
            'total_pages' => $totalPages,
        ]);
    }
}