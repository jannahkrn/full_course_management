@extends('layouts.admin')
@section('title','Kategori Mata Kuliah')
@section('breadcrumb')<span class="text-gray-800 font-medium text-sm">Kategori Mata Kuliah</span>@endsection

@section('content')
<div class="p-6 space-y-5">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Kategori Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola kategori mata kuliah di Smart Exam.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="flex gap-2.5">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="keyword" value="{{ $keyword }}" placeholder="Cari kategori..." class="input-field pl-9 text-sm">
                </div>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">List</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="w-10 px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Slug</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Jumlah Mata Kuliah</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $cat)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-4 py-3.5"><input type="checkbox" class="rounded border-gray-300" value="{{ $cat->id }}"></td>
                        <td class="px-4 py-3.5 font-semibold text-gray-900 text-[13px]">{{ $cat->name }}</td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-500 font-mono">{{ $cat->slug }}</td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-600">{{ $cat->courses_count }} mata kuliah</td>
                        <td class="px-4 py-3.5">
                            @if($cat->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <div x-data="{ open:false }" class="relative inline-block">
                                <button @click="open=!open" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-600">
                                    <span class="w-2 h-2 rounded-full {{ $cat->is_active?'bg-green-500':'bg-gray-400' }}"></span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.outside="open=false" x-cloak
                                     class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-20">
                                    <a href="{{ route('admin.categories.edit', $cat) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Ubah Kategori
                                    </a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-16 text-center text-sm text-gray-400">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($categories->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan {{ $categories->firstItem() }} s.d. {{ $categories->lastItem() }} dari total {{ $categories->total() }}</span>
            <div class="flex items-center gap-1">
                @foreach($categories->links()->elements[0] as $p => $url)
                    <a href="{{ $url }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold border transition-colors {{ $p==$categories->currentPage()?'bg-blue-600 border-blue-600 text-white':'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $p }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
