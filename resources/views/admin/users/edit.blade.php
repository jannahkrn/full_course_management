@extends('layouts.admin')
@section('title', 'Ubah Pengguna')
@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Manajemen Pengguna</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">Ubah Pengguna</span>
@endsection

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Ubah Pengguna</h1>

    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap"
                       class="input-field @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan email"
                       class="input-field @error('email') border-red-400 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah"
                       class="input-field @error('password') border-red-400 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" class="select-field">
                    <option value="admin"   {{ old('role', $user->role) === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role', $user->role) === 'teacher' ? 'selected' : '' }}>Guru</option>
                    <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Masukkan nomor telepon" class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Departemen</label>
                <input type="text" name="department" value="{{ old('department', $user->department) }}" placeholder="Masukkan departemen" class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
