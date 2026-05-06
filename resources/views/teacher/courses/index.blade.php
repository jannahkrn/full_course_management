@extends('layouts.app')
@section('title', 'Katalog Mata Kuliah')
@section('breadcrumb')<span class="text-gray-800 font-medium text-sm">Katalog Mata Kuliah</span>@endsection

@section('sidebar-nav')
    <div class="nav-section-label">Mata Kuliah</div>

    <a href="{{ route('teacher.courses.index') }}"
       class="nav-item {{ request()->routeIs('teacher.courses.index') ? 'active' : '' }}">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Mata Kuliah Saya</span>
    </a>

    <a href="{{ route('teacher.courses.index') }}"
       class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Katalog</span>
    </a>

    <div class="nav-section-label" x-show="sidebarOpen" x-cloak>Pengelolaan</div>
    <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 4v16m8-8H4"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Buat Mata Kuliah</span>
    </a>
    <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Kelola Sesi</span>
    </a>

    <div class="nav-section-label" x-show="sidebarOpen" x-cloak>Aktivitas</div>
    <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Terakhir Dikunjungi</span>
    </a>
    <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Riwayat</span>
    </a>
    <a href="#" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span x-show="sidebarOpen" x-cloak>Laporan Waktu</span>
    </a>
@endsection

@section('content')
<div class="p-6 space-y-5">

    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Katalog Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-1">Jelajahi berbagai materi pembelajaran menarik dan temukan mata kuliah yang ingin anda kelola hari ini.</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('teacher.courses.index') }}">
            <div class="flex gap-2.5 flex-wrap items-end">
                <div class="flex-1 min-w-48">
                    <label class="block text-xs text-gray-500 mb-1.5">Nama Mata Kuliah</label>
                    <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                           placeholder="Masukkan nama mata kuliah" class="input-field text-sm">
                </div>
                <div class="w-44">
                    <label class="block text-xs text-gray-500 mb-1.5">Kategori</label>
                    <select name="category_id" class="select-field text-sm">
                        <option value="">Semua</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-44">
                    <label class="block text-xs text-gray-500 mb-1.5">Urutkan</label>
                    <select name="sort_by" class="select-field text-sm">
                        <option value="">Tidak Ada</option>
                        <option value="title"      {{ ($filters['sort_by'] ?? '') === 'title'      ? 'selected' : '' }}>Judul</option>
                        <option value="created_at" {{ ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary px-4 py-2 text-sm self-end">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri
                </button>
            </div>

            {{-- Active filters --}}
            @if(array_filter($filters))
            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-500 self-center">Difilter berdasarkan:</span>
                @if(!empty($filters['category_id']))
                    @php $cat = $categories->find($filters['category_id']); @endphp
                    @if($cat)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">
                        Kategori: {{ $cat->name }}
                        <a href="{{ route('teacher.courses.index', array_merge($filters, ['category_id' => ''])) }}" class="ml-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </span>
                    @endif
                @endif
                @if(!empty($filters['sort_by']))
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">
                        Sort By: {{ ucfirst($filters['sort_by']) }}
                        <a href="{{ route('teacher.courses.index', array_merge($filters, ['sort_by' => ''])) }}" class="ml-0.5">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </span>
                @endif
            </div>
            @endif
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">List</span>
            <span class="text-xs text-gray-400">Menampilkan {{ $courses->count() }} dari {{ $courses->total() }} data</span>
        </div>

        @forelse($courses as $course)
        <div class="flex items-center gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50/50 transition-colors last:border-0">
            {{-- Checkbox + Thumbnail --}}
            <input type="checkbox" class="rounded border-gray-300 flex-shrink-0">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <a href="{{ route('teacher.courses.show', $course) }}"
                       class="font-semibold text-[13px] text-gray-900 hover:text-blue-600 transition-colors truncate">
                        {{ $course->title }}
                    </a>
                    @if($course->category)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-600 flex-shrink-0">
                        {{ $course->category->name }}
                    </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 truncate">{{ $course->teachers->pluck('name')->join(', ') }}</p>
                <div class="flex items-center gap-3 mt-1">
                    <span class="flex items-center gap-1 text-xs text-gray-500">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        {{ $course->active_enrollments_count }} Mahasiswa
                    </span>
                </div>
            </div>

            {{-- Toggle aktif --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">{{ $course->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                    <div class="w-9 h-5 rounded-full relative {{ $course->is_active ? 'bg-blue-500' : 'bg-gray-300' }} cursor-not-allowed">
                        <div class="w-4 h-4 bg-white rounded-full absolute top-0.5 shadow-sm transition-transform {{ $course->is_active ? 'translate-x-4' : 'translate-x-0.5' }}"></div>
                    </div>
                </div>
                <a href="{{ route('teacher.courses.show', $course) }}"
                   class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors" title="Lihat Detail">
                    <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/></svg>
                </a>
            </div>
        </div>
        @empty
        <div class="px-5 py-16 text-center">
            <div class="flex flex-col items-center gap-3 text-gray-400">
                <svg class="w-14 h-14 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                <span class="text-sm font-medium">Belum ada mata kuliah</span>
            </div>
        </div>
        @endforelse

        @if($courses->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan {{ $courses->firstItem() }} s.d. {{ $courses->lastItem() }} dari {{ $courses->total() }}</span>
            <div class="flex items-center gap-1">
                @foreach(range(max(1,$courses->currentPage()-2),min($courses->lastPage(),$courses->currentPage()+2)) as $p)
                    <a href="{{ $courses->url($p) }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold border {{ $p===$courses->currentPage()?'bg-blue-600 border-blue-600 text-white':'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $p }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
