<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * GET /api/admin/users
     * Supports: keyword, role, is_active, per_page
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($keyword = $request->get('keyword')) {
            $query->where(fn($q) =>
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
            );
        }
        if ($role = $request->get('role')) {
            $query->where('role', $role);
        }
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $perPage = (int) $request->get('per_page', 10);
        $users   = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'data' => UserResource::collection($users),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
                'per_page'     => $users->perPage(),
                'total'        => $users->total(),
            ],
        ]);
    }

    /**
     * GET /api/admin/users/{user}
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(['data' => new UserResource($user)]);
    }

    /**
     * POST /api/admin/users
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,teacher,student',
            'department' => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        return response()->json([
            'message' => 'Pengguna berhasil dibuat.',
            'data'    => new UserResource($user),
        ], 201);
    }

    /**
     * PUT /api/admin/users/{user}
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'email'      => ['sometimes', 'required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'   => 'sometimes|nullable|string|min:8',
            'role'       => 'sometimes|in:admin,teacher,student',
            'department' => 'sometimes|nullable|string|max:255',
            'phone'      => 'sometimes|nullable|string|max:20',
            'is_active'  => 'sometimes|boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui.',
            'data'    => new UserResource($user),
        ]);
    }

    /**
     * DELETE /api/admin/users/{user}
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['message' => 'Pengguna berhasil dihapus.']);
    }

    /**
     * GET /api/admin/users/{user}/courses
     * Show courses for a specific user.
     */
    public function courses(User $user): JsonResponse
    {
        $courses = $user->enrolledCourses()
                        ->with('category')
                        ->wherePivot('status', 'active')
                        ->get();

        return response()->json([
            'data' => \App\Http\Resources\CourseResource::collection($courses),
        ]);
    }

    /**
     * GET /api/admin/teachers
     * Quick list of all teachers (for dropdowns).
     */
    public function teachers(Request $request): JsonResponse
    {
        $users = User::teachers()
                     ->active()
                     ->when($request->keyword, fn($q) =>
                         $q->where('name', 'like', "%{$request->keyword}%")
                     )
                     ->orderBy('name')
                     ->get(['id', 'name', 'email', 'avatar']);

        return response()->json(['data' => UserResource::collection($users)]);
    }
}