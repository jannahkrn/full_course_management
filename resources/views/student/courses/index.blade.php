@extends('layouts.app')
@section('title', 'Daftar Mata Kuliah Saya')

@section('sidebar-nav')

    <div x-data="{ open: true }">
        <button @click="open = !open"
                class="nav-item w-full text-left {{ request()->routeIs('student.courses.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/>
                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
            </svg>
            <span x-show="sidebarOpen" x-cloak class="flex-1">Mata Kuliah</span>
            <svg x-show="sidebarOpen" x-cloak
                 class="w-3.5 h-3.5 transition-transform flex-shrink-0"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open && sidebarOpen" x-cloak class="mt-0.5 space-y-0.5">
            <a href="{{ route('student.courses.index') }}"
               class="nav-sub-item {{ request()->routeIs('student.courses.index') ? 'active' : '' }}">
                Mata Kuliah Saya
            </a>
            <a href="#" class="nav-sub-item">Katalog</a>
        </div>
    </div>

    <div x-data="{ open: true }">
        <button @click="open = !open" class="nav-item w-full text-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                <path d="M21 12c0 1.66-4.03 3-9 3S3 13.66 3 12"/>
                <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/>
            </svg>
            <span x-show="sidebarOpen" x-cloak class="flex-1">Pengelolaan</span>
            <svg x-show="sidebarOpen" x-cloak
                 class="w-3.5 h-3.5 transition-transform flex-shrink-0"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open && sidebarOpen" x-cloak class="mt-0.5 space-y-0.5">
            <a href="#" class="nav-sub-item">Buat Mata Kuliah</a>
            <a href="#" class="nav-sub-item">Kelola Sesi</a>
        </div>
    </div>

    <div x-data="{ open: true }">
        <button @click="open = !open" class="nav-item w-full text-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.21 15.89A10 10 0 118 2.83"/>
                <path d="M22 12A10 10 0 0012 2v10z"/>
            </svg>
            <span x-show="sidebarOpen" x-cloak class="flex-1">Aktivitas</span>
            <svg x-show="sidebarOpen" x-cloak
                 class="w-3.5 h-3.5 transition-transform flex-shrink-0"
                 :class="open ? 'rotate-180' : ''"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>
        <div x-show="open && sidebarOpen" x-cloak class="mt-0.5 space-y-0.5">
            <a href="#" class="nav-sub-item">Terakhir Dikunjungi</a>
            <a href="#" class="nav-sub-item">Riwayat</a>
            <a href="#" class="nav-sub-item">Laporan Waktu</a>
        </div>
    </div>

@endsection

@section('content')
<div class="p-6 space-y-5" x-data="{ viewMode: '{{ $view }}' }">

    <div>
        <h1 class="text-[22px] font-bold text-gray-900">Daftar Mata Kuliah Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Temukan semua kelas yang kamu ikuti di sini dan mari lanjutkan progres belajarmu hari ini.</p>
    </div>

    <form method="GET" action="{{ route('student.courses.index') }}">
        <div class="flex gap-2.5 items-end flex-wrap">
            <div class="flex-1 min-w-48">
                <label class="block text-xs text-gray-500 mb-1.5">Nama Mata Kuliah</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}"
                           placeholder="Masukkan nama mata kuliah"
                           class="input-field pl-9 text-sm">
                </div>
            </div>
            <div class="w-44">
                <label class="block text-xs text-gray-500 mb-1.5">Kategori</label>
                <div class="relative" x-data="{ open: false }">
                    <button type="button" @click="open=!open"
                            class="input-field text-sm text-left flex items-center justify-between">
                        <span>
                            @if(!empty($filters['category_id']))
                                {{ $categories->find($filters['category_id'])?->name ?? 'Filter Dr...' }}
                            @else
                                Filter Dr...
                            @endif
                        </span>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.outside="open=false" x-cloak
                         class="absolute left-0 mt-1 w-full bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-20">
                        <a href="{{ route('student.courses.index', array_merge($filters, ['category_id' => ''])) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ empty($filters['category_id'])?'font-semibold text-blue-600':'' }}">Semua</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('student.courses.index', array_merge($filters, ['category_id' => $cat->id])) }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ ($filters['category_id'] ?? '') == $cat->id ?'font-semibold text-blue-600':'' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                    <input type="hidden" name="category_id" value="{{ $filters['category_id'] ?? '' }}">
                </div>
            </div>
            <button type="submit" class="btn-primary px-4 py-2 text-sm self-end">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Telusuri
            </button>
        </div>
    </form>

    <div class="flex items-center justify-between">
        <span class="text-sm text-gray-500">Menampilkan {{ $courses->count() }} data</span>
        <div class="flex items-center gap-1">
            @if(!empty($filters['category_id']))
                @php $cat = $categories->find($filters['category_id']); @endphp
                @if($cat)
                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-100">
                    Kategori: {{ $cat->name }}
                    <a href="{{ route('student.courses.index', array_merge($filters, ['category_id' => ''])) }}">
                        <svg class="w-3 h-3 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </a>
                </span>
                @endif
            @endif

            <div class="flex items-center gap-1 ml-2 border border-gray-200 rounded-lg p-0.5">
                <button @click="viewMode='list'" :class="viewMode==='list'?'bg-gray-100 text-gray-900':'text-gray-400'"
                        class="p-1.5 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </button>
                <button @click="viewMode='grid'" :class="viewMode==='grid'?'bg-gray-100 text-gray-900':'text-gray-400'"
                        class="p-1.5 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="viewMode==='grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($courses as $course)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="h-32 bg-gradient-to-br from-blue-400 to-blue-600 relative overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-white opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                @endif
                @if($course->category)
                <div class="absolute top-2 left-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-white/90 backdrop-blur text-[10px] font-semibold text-blue-700 rounded-full">
                        {{ $course->category->name }}
                    </span>
                </div>
                @endif
            </div>

            <div class="p-4">
                <h3 class="font-bold text-[13px] text-gray-900 leading-snug mb-1 line-clamp-2">{{ $course->title }}</h3>
                <p class="text-xs text-gray-500 mb-3 truncate">
                    {{ $course->teachers->first()?->name ?? '-' }}
                    @if($course->active_enrollments_count)
                        · {{ $course->active_enrollments_count }} Mahasiswa
                    @endif
                </p>
                <a href="{{ route('student.courses.show', $course) }}"
                   class="w-full btn-primary justify-center text-xs py-2">
                    Masuk Kelas
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-4 py-16 text-center text-gray-400">
            <div class="flex flex-col items-center gap-3">
                <svg class="w-14 h-14 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/></svg>
                <span class="text-sm font-medium">Belum ada mata kuliah yang diikuti</span>
            </div>
        </div>
        @endforelse
    </div>

    <div x-show="viewMode==='list'" class="space-y-3">
        @forelse($courses as $course)
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4 hover:shadow-sm transition-shadow">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0 overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ Storage::url($course->thumbnail) }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13"/></svg>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <h3 class="font-bold text-[13px] text-gray-900 truncate">{{ $course->title }}</h3>
                    @if($course->category)
                        <span class="inline-flex items-center px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-semibold rounded flex-shrink-0">{{ $course->category->name }}</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500">{{ $course->teachers->first()?->name ?? '-' }}</p>
                @if($course->active_enrollments_count)
                    <span class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0"/></svg>
                        {{ $course->active_enrollments_count }} Mahasiswa
                    </span>
                @endif
            </div>
            <a href="{{ route('student.courses.show', $course) }}" class="btn-primary text-xs py-2 flex-shrink-0">
                Masuk Kelas
            </a>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center text-gray-400">
            <span class="text-sm">Belum ada mata kuliah</span>
        </div>
        @endforelse
    </div>

    @if($courses->hasPages())
    <div class="flex justify-center">
        <div class="flex items-center gap-1">
            @foreach(range(max(1,$courses->currentPage()-2),min($courses->lastPage(),$courses->currentPage()+2)) as $p)
                <a href="{{ $courses->url($p) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold border {{ $p===$courses->currentPage()?'bg-blue-600 border-blue-600 text-white':'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $p }}</a>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection