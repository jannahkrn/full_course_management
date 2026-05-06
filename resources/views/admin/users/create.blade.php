@extends('layouts.admin')
@section('title', 'Tambah Pengguna')
@section('breadcrumb')
    <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Manajemen Pengguna</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">Tambah Pengguna</span>
@endsection

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Tambah Pengguna</h1>

    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                       class="input-field @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email"
                       class="input-field @error('email') border-red-400 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" placeholder="Minimal 8 karakter"
                       class="input-field @error('password') border-red-400 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" placeholder="Ulangi password"
                       class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                <select name="role" class="select-field @error('role') border-red-400 @enderror">
                    <option value="">Pilih role...</option>
                    <option value="admin"   {{ old('role') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ old('role') === 'teacher' ? 'selected' : '' }}>Guru</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon"
                       class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Departemen</label>
                <input type="text" name="department" value="{{ old('department') }}" placeholder="Masukkan departemen"
                       class="input-field">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Pengguna
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
