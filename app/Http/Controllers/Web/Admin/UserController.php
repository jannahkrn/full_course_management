<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when($request->keyword, fn($q, $v) => $q->where('name', 'like', "%{$v}%")->orWhere('email', 'like', "%{$v}%"))
                     ->when($request->role, fn($q, $v) => $q->where('role', $v))
                     ->orderBy('name')
                     ->paginate((int) $request->get('per_page', 10));

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:admin,teacher,student',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,teacher,student',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.mimes' => 'File harus berformat CSV (.csv). Format Excel (.xlsx/.xls) belum didukung untuk import.',
        ]);

        $file   = $request->file('file');
        $path   = $file->getRealPath();

        $imported = 0;
        $skipped  = 0;
        $errors   = [];

        $handle = fopen($path, 'r');

        $header = fgetcsv($handle);

        if ($header && str_starts_with($header[0], "\xEF\xBB\xBF")) {
            $header[0] = substr($header[0], 3);
        }

        $lineNo = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $lineNo++;

            if (empty(array_filter($row))) {
                $skipped++;
                continue;
            }

            $name     = trim($row[0] ?? '');
            $email    = trim($row[1] ?? '');
            $role     = trim($row[2] ?? 'student');
            $password = trim($row[3] ?? '') ?: 'password123';

            if (empty($name) || empty($email)) {
                $skipped++;
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Baris {$lineNo}: Email tidak valid ({$email})";
                continue;
            }

            if (!in_array($role, ['admin', 'teacher', 'student'])) {
                $role = 'student';
            }

            if (User::where('email', $email)->exists()) {
                $skipped++;
                continue;
            }

            try {
                User::create([
                    'name'     => $name,
                    'email'    => $email,
                    'role'     => $role,
                    'password' => Hash::make($password),
                ]);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Baris {$lineNo} (\"{$email}\"): " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "{$imported} pengguna berhasil diimpor.";
        if ($skipped)       $message .= " {$skipped} baris dilewati.";
        if (count($errors)) $message .= " " . count($errors) . " baris gagal.";

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}