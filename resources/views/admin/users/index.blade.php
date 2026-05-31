@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('breadcrumb')<span class="text-gray-800 font-medium text-sm">Manajemen Pengguna</span>@endsection

@section('content')
<div class="p-6 space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh pengguna di Smart Exam.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="flex gap-2.5 items-center">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                           placeholder="Cari nama atau email..."
                           class="input-field pl-9 text-sm">
                </div>
                <select name="role" class="select-field w-40 text-sm">
                    <option value="">Semua Role</option>
                    <option value="admin"   {{ ($filters['role'] ?? '') === 'admin'   ? 'selected' : '' }}>Admin</option>
                    <option value="teacher" {{ ($filters['role'] ?? '') === 'teacher' ? 'selected' : '' }}>Guru</option>
                    <option value="student" {{ ($filters['role'] ?? '') === 'student' ? 'selected' : '' }}>Mahasiswa</option>
                </select>
                <select name="is_active" class="select-field w-36 text-sm">
                    <option value="">Semua Status</option>
                    <option value="1" {{ ($filters['is_active'] ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ ($filters['is_active'] ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri
                </button>
                @if(array_filter($filters))
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary px-3 py-2 text-sm">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">List</span>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xs">Tampilkan</span>
                <form method="GET" action="{{ route('admin.users.index') }}" class="inline">
                    @foreach(request()->except('per_page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="per_page" onchange="this.form.submit()"
                            class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none bg-white">
                        @foreach([10,25,50] as $n)
                            <option value="{{ $n }}" {{ request('per_page',10)==$n?'selected':'' }}>{{ $n }} Data</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="w-10 px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Departemen</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-4 py-3.5"><input type="checkbox" class="rounded border-gray-300" value="{{ $user->id }}"></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'teacher' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-gray-900 text-[13px]">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role === 'teacher' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-600">{{ $user->department ?: '-' }}</td>
                        <td class="px-4 py-3.5">
                            @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-600">
                                    <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-20">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Ubah Pengguna
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-14 h-14 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                                <span class="text-sm font-medium">Belum ada pengguna</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan {{ $users->firstItem() }} s.d. {{ $users->lastItem() }} dari total {{ $users->total() }}</span>
            <div class="flex items-center gap-1">
                <a href="{{ $users->url(1) }}" class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 {{ $users->onFirstPage()?'opacity-30 pointer-events-none':'' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                </a>
                <a href="{{ $users->previousPageUrl() ?? '#' }}" class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 {{ $users->onFirstPage()?'opacity-30 pointer-events-none':'' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                @foreach(range(max(1,$users->currentPage()-2), min($users->lastPage(),$users->currentPage()+2)) as $p)
                    <a href="{{ $users->url($p) }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold border transition-colors {{ $p===$users->currentPage()?'bg-blue-600 border-blue-600 text-white':'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $p }}</a>
                @endforeach
                <a href="{{ $users->nextPageUrl() ?? '#' }}" class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 {{ !$users->hasMorePages()?'opacity-30 pointer-events-none':'' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ $users->url($users->lastPage()) }}" class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 {{ !$users->hasMorePages()?'opacity-30 pointer-events-none':'' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
