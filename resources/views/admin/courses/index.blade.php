@extends('layouts.admin')

@section('title', 'Daftar Mata Kuliah')

@section('breadcrumb')
<span class="text-gray-800 font-medium text-sm">Daftar Mata Kuliah</span>
@endsection

@section('content')
<div class="p-6 space-y-5">

    {{-- ── Page Header ────────────────────────────────────────── --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900 leading-tight">Daftar Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola seluruh mata kuliah di Smart Exam.</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Mata Kuliah
        </a>
    </div>

    {{-- ── Tabs ────────────────────────────────────────────────── --}}
    <div class="border-b border-gray-200">
        <nav class="flex gap-0">
            <a href="{{ route('admin.courses.index', array_merge(request()->except('view','page'), ['view' => 'standar'])) }}"
               class="px-4 pb-3 text-sm font-semibold border-b-2 transition-colors
                      {{ $view === 'standar'
                            ? 'border-blue-600 text-blue-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                List Standar
            </a>
            <a href="{{ route('admin.courses.index', array_merge(request()->except('view','page'), ['view' => 'manajemen'])) }}"
               class="px-4 pb-3 text-sm font-semibold border-b-2 transition-colors
                      {{ $view === 'manajemen'
                            ? 'border-blue-600 text-blue-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                List Manajemen
            </a>
        </nav>
    </div>

    {{-- ── Filter ──────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4" x-data="{ showAdvanced: false }">
        <form method="GET" action="{{ route('admin.courses.index') }}" id="filterForm">
            <input type="hidden" name="view" value="{{ $view }}">

            {{-- Basic row --}}
            <div class="flex gap-2.5 items-center">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                           placeholder="Masukkan nama mata kuliah"
                           class="input-field pl-9 text-sm">
                </div>
                <button type="button" @click="showAdvanced = !showAdvanced"
                        class="btn-secondary px-3 py-2" title="Filter lanjutan">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
                    </svg>
                </button>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Telusuri
                </button>
            </div>

            {{-- Advanced Search --}}
            <div x-show="showAdvanced" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Advanced Search</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Judul</label>
                        <input type="text" name="title" value="{{ $filters['title'] ?? '' }}"
                               placeholder="Masukkan judul mata kuliah" class="input-field text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Kode</label>
                        <input type="text" name="code" value="{{ $filters['code'] ?? '' }}"
                               placeholder="Masukkan kode mata kuliah" class="input-field text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Kategori Mata Kuliah</label>
                        <select name="category_id" class="select-field text-sm">
                            <option value="">Pilih kategori mata kuliah</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Bahasa Mata Kuliah</label>
                        <select name="language" class="select-field text-sm">
                            <option value="">Semua</option>
                            <option value="id" {{ ($filters['language'] ?? '') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            <option value="en" {{ ($filters['language'] ?? '') === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Akses Mata Kuliah</label>
                        <select name="access_type" class="select-field text-sm">
                            <option value="">Semua</option>
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                            <option value="restricted">Restricted</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Pendaftara</label>
                        <select name="is_registered" class="select-field text-sm">
                            <option value="">Semua</option>
                            <option value="1" {{ ($filters['is_registered'] ?? '') === '1' ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ ($filters['is_registered'] ?? '') === '0' ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5">Berhenti Berlangganan</label>
                        <select name="allow_unsubscribe" class="select-field text-sm">
                            <option value="">Semua</option>
                            <option value="1">Diizinkan</option>
                            <option value="0">Tidak Diizinkan</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <a href="{{ route('admin.courses.index', ['view' => $view]) }}" class="btn-secondary text-xs py-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Atur Ulang
                    </a>
                    <button type="submit" class="btn-primary text-xs py-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Telusuri
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Table ───────────────────────────────────────────────── --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- Table toolbar --}}
        <div class="px-5 py-3 flex items-center justify-between border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">List</span>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span class="text-xs">Tampilkan</span>
                <form method="GET" action="{{ route('admin.courses.index') }}" class="inline">
                    @foreach(request()->except('per_page') as $k => $v)
                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                    @endforeach
                    <select name="per_page" onchange="this.form.submit()"
                            class="text-xs border border-gray-200 rounded-lg px-2.5 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                        @foreach([10, 25, 50] as $n)
                            <option value="{{ $n }}" {{ request('per_page', 10) == $n ? 'selected' : '' }}>
                                {{ $n }} Data
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="w-10 px-4 py-3 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600">
                        </th>

                        @if($view === 'standar')
                        {{-- ══ LIST STANDAR HEADERS ══ --}}
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Bahasa
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Kategori
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Terdaftar Diizinkan
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Tidak Terdaftar Diizinkan
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>

                        @else
                        {{-- ══ LIST MANAJEMEN HEADERS ══ --}}
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Judul</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Guru</th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Tanggal Pembuatan
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left">
                            <button class="flex items-center gap-1 text-[11px] font-bold text-gray-500 uppercase tracking-wider hover:text-gray-700">
                                Akses Terakhir
                                <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                                </svg>
                            </button>
                        </th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($courses as $course)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-4 py-3.5">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600" value="{{ $course->id }}">
                        </td>

                        @if($view === 'standar')
                        {{-- ══ LIST STANDAR CELLS ══ --}}
                        <td class="px-4 py-3.5">
                            <div class="font-semibold text-gray-900 text-[13px]">{{ $course->title }}</div>
                            <div class="text-[11px] text-gray-400 font-medium uppercase tracking-wider mt-0.5">
                                {{ $course->code ?? $course->slug }}
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-600">
                            {{ $course->language === 'id' ? 'Bahasa Indonesia' : 'English' }}
                        </td>
                        <td class="px-4 py-3.5">
                            @if($course->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                    {{ $course->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400 text-[13px]">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            @if($course->is_registered)
                                <span class="badge-ya">Ya</span>
                            @else
                                <span class="badge-tidak">Tidak</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            @if($course->is_allowed)
                                <span class="badge-ya">Ya</span>
                            @else
                                <span class="badge-tidak">Tidak</span>
                            @endif
                        </td>

                        {{-- AKSI — List Standar --}}
                        <td class="px-4 py-3.5">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-600 transition-colors">
                                    <span class="w-2 h-2 rounded-full {{ $course->is_active ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-20">

                                    {{-- Situs Mata Kuliah --}}
                                    <a href="{{ route('admin.courses.show', $course) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Situs Mata Kuliah
                                    </a>

                                    {{-- Penelusuran --}}
                                    <a href="{{ route('admin.courses.show', $course) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        Penelusuran
                                    </a>

                                    {{-- Ubah --}}
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Ubah Mata Kuliah
                                    </a>

                                    {{-- Buat Cadangan --}}
                                    <form action="{{ route('admin.courses.duplicate', $course) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Buat Cadangan
                                        </button>
                                    </form>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Mata Kuliah
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>

                        @else
                        {{-- ══ LIST MANAJEMEN CELLS ══ --}}
                        <td class="px-4 py-3.5">
                            <div class="font-semibold text-gray-900 text-[13px]">{{ $course->title }}</div>
                            <div class="text-[11px] text-gray-400 font-medium uppercase tracking-wider mt-0.5">
                                {{ $course->code ?? $course->slug }}
                            </div>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex flex-wrap items-center gap-1.5">
                                @forelse($course->teachers->take(4) as $teacher)
                                    <span class="inline-flex items-center gap-1 text-[12px] text-gray-600">
                                        <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        {{ $teacher->name }}
                                    </span>
                                @empty
                                    <span class="text-[12px] text-gray-400">-</span>
                                @endforelse
                                @if($course->teachers->count() > 4)
                                    <span class="text-[11px] text-gray-400">+{{ $course->teachers->count() - 4 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[12px] text-gray-600 whitespace-nowrap">
                            {{ $course->created_at->format('d M Y,') }}<br>
                            <span class="text-gray-400">{{ $course->created_at->format('H:i') }}</span>
                        </td>
                        <td class="px-4 py-3.5 text-[12px] text-gray-600 whitespace-nowrap">
                            {{ $course->updated_at->format('d M Y,') }}<br>
                            <span class="text-gray-400">{{ $course->updated_at->format('H:i') }}</span>
                        </td>

                        {{-- AKSI — List Manajemen (tanpa Situs Mata Kuliah) --}}
                        <td class="px-4 py-3.5">
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = !open"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-xs font-medium text-gray-600 transition-colors">
                                    <span class="w-2 h-2 rounded-full {{ $course->is_active ? 'bg-blue-500' : 'bg-gray-400' }}"></span>
                                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-cloak
                                     class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1.5 z-20">

                                    {{-- Penelusuran --}}
                                    <a href="{{ route('admin.courses.show', $course) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                        Penelusuran
                                    </a>

                                    {{-- Ubah --}}
                                    <a href="{{ route('admin.courses.edit', $course) }}"
                                       class="flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Ubah Mata Kuliah
                                    </a>

                                    {{-- Buat Cadangan --}}
                                    <form action="{{ route('admin.courses.duplicate', $course) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                            </svg>
                                            Buat Cadangan
                                        </button>
                                    </form>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="w-full flex items-center gap-3 px-4 py-2 text-[13px] text-red-600 hover:bg-red-50">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Hapus Mata Kuliah
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        @endif

                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $view === 'standar' ? 7 : 6 }}" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-14 h-14 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                                </svg>
                                <span class="text-sm font-medium">Belum ada mata kuliah</span>
                                <a href="{{ route('admin.courses.create') }}" class="btn-primary text-xs mt-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Tambah Mata Kuliah
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ─────────────────────────────────────── --}}
        @if($courses->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">
                Menampilkan {{ $courses->firstItem() }} s.d. {{ $courses->lastItem() }} dari total {{ $courses->total() }}
            </span>
            <div class="flex items-center gap-1">
                {{-- First --}}
                <a href="{{ $courses->url(1) }}"
                   class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors {{ $courses->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                </a>
                {{-- Prev --}}
                <a href="{{ $courses->previousPageUrl() ?? '#' }}"
                   class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors {{ $courses->onFirstPage() ? 'opacity-30 pointer-events-none' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>

                @foreach(range(max(1, $courses->currentPage() - 2), min($courses->lastPage(), $courses->currentPage() + 2)) as $page)
                    <a href="{{ $courses->url($page) }}"
                       class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold border transition-colors
                              {{ $page === $courses->currentPage()
                                    ? 'bg-blue-600 border-blue-600 text-white'
                                    : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $page }}
                    </a>
                @endforeach

                {{-- Next --}}
                <a href="{{ $courses->nextPageUrl() ?? '#' }}"
                   class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors {{ !$courses->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                {{-- Last --}}
                <a href="{{ $courses->url($courses->lastPage()) }}"
                   class="p-1.5 rounded-lg border border-gray-200 text-gray-500 hover:bg-gray-50 transition-colors {{ !$courses->hasMorePages() ? 'opacity-30 pointer-events-none' : '' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
        @else
        <div class="px-5 py-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">
                Menampilkan {{ $courses->count() }} dari total {{ $courses->total() }}
            </span>
        </div>
        @endif

    </div>
</div>
@endsection