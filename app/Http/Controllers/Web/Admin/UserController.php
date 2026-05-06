<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['keyword', 'role', 'is_active']);
        $perPage = (int) $request->get('per_page', 10);

        $query = User::query();

        if (!empty($filters['keyword'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['keyword']}%")
                  ->orWhere('email', 'like', "%{$filters['keyword']}%");
            });
        }
        if (isset($filters['role']) && $filters['role'] !== '') {
            $query->where('role', $filters['role']);
        }
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        $users = $query->orderBy('name')->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required|in:admin,teacher,student',
            'phone'      => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'is_active'  => 'boolean',
        ]);

        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$data['name']}\" berhasil dibuat.");
    }

    public function show(User $user)
    {
        $user->load(['enrollments.course', 'teachingCourses']);
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password'   => 'nullable|min:8|confirmed',
            'role'       => 'required|in:admin,teacher,student',
            'phone'      => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'is_active'  => 'boolean',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$name}\" berhasil dihapus.");
    }
}