@extends('layouts.app')
@section('title', $course->title)
@section('breadcrumb')
    <a href="{{ route('student.courses.index') }}" class="hover:text-blue-600">Pengelolaan Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">{{ $course->title }}</span>
@endsection

@section('sidebar-nav')
    <div x-data="{ open: true }">
        <button @click="open=!open" class="nav-item w-full text-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            <span x-show="sidebarOpen" x-cloak class="flex-1">Mata Kuliah</span>
            <svg x-show="sidebarOpen" x-cloak class="w-3.5 h-3.5 transition-transform" :class="open?'rotate-180':''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open && sidebarOpen" x-cloak class="mt-0.5 space-y-0.5">
            <a href="{{ route('student.courses.index') }}" class="nav-sub-item active">Mata Kuliah Saya</a>
            <a href="#" class="nav-sub-item">Katalog</a>
        </div>
    </div>
    <div class="nav-section-label" x-show="sidebarOpen" x-cloak>Aktivitas</div>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span x-show="sidebarOpen" x-cloak>Terakhir Dikunjungi</span></a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg><span x-show="sidebarOpen" x-cloak>Riwayat</span></a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><span x-show="sidebarOpen" x-cloak>Laporan Waktu</span></a>
@endsection

@section('content')
<div class="p-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-[22px] font-bold text-gray-900">{{ $course->title }}</h1>
                @if($course->category)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold
                        {{ $course->category->name === 'Projek' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
                        {{ $course->category->name === 'Projek' ? '✕' : '◎' }} {{ $course->category->name }}
                    </span>
                @endif
            </div>
            <p class="text-sm text-gray-500">
                {{ $course->teachers->first()?->name ?? '-' }}
                @if($course->teachers->first()?->department)
                    , {{ $course->teachers->first()->department }}
                @endif
                @php $enrollCount = $course->sessions->first()?->course?->active_enrollments_count ?? 0; @endphp
                · 34 Mahasiswa
            </p>
        </div>
        <a href="{{ route('student.courses.index') }}" class="btn-secondary text-sm">← Kembali</a>
    </div>

    {{-- Tabs: Materi + Latihan --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ tab: 'materi' }">
        <div class="flex border-b border-gray-100">
            <button @click="tab='materi'"
                    :class="tab==='materi' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 text-sm font-semibold transition-colors flex items-center gap-2.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div class="text-left">
                    <div>Materi</div>
                    <div class="text-[10px] font-normal text-gray-400">Description</div>
                </div>
            </button>
            <button @click="tab='latihan'"
                    :class="tab==='latihan' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-4 text-sm font-semibold transition-colors flex items-center gap-2.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <div class="text-left">
                    <div>Latihan</div>
                    <div class="text-[10px] font-normal text-gray-400">Description</div>
                </div>
            </button>
        </div>

        <div class="p-6">
            {{-- MATERI --}}
            <div x-show="tab==='materi'">
                @if($course->sessions->count())
                    <div class="space-y-3">
                        @foreach($course->sessions as $session)
                        <div class="border border-gray-100 rounded-xl overflow-hidden"
                             x-data="{ open: false }">
                            <button @click="open=!open"
                                    class="w-full flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 transition-colors text-left">
                                <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ $session->order + 1 }}
                                </div>
                                <span class="flex-1 font-semibold text-[13px] text-gray-900">{{ $session->title }}</span>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    @if($session->materials->count())
                                        <span>{{ $session->materials->count() }} materi</span>
                                    @endif
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform flex-shrink-0" :class="open?'rotate-180':''"
                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak class="border-t border-gray-50">
                                @forelse($session->materials as $mat)
                                <div class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                                    <div class="w-6 h-6 flex items-center justify-center flex-shrink-0">
                                        @if($mat->type === 'video')
                                            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-[13px] text-gray-700">{{ $mat->title }}</span>
                                </div>
                                @empty
                                <div class="px-4 py-3 text-xs text-gray-400">Belum ada materi</div>
                                @endforelse
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center gap-3 py-16 text-gray-400">
                        <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm font-medium">Belum ada materi</span>
                    </div>
                @endif
            </div>

            {{-- LATIHAN --}}
            <div x-show="tab==='latihan'">
                @php $exercises = $course->sessions->flatMap->exercises; @endphp
                @if($exercises->count())
                    <div class="space-y-3">
                        @foreach($exercises as $ex)
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-[13px] text-gray-900">{{ $ex->title }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ $ex->type }} · {{ $ex->duration_minutes ?? '-' }} menit · Skor max: {{ $ex->max_score }}</p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center gap-3 py-16 text-gray-400">
                        <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        <span class="text-sm font-medium">Belum ada latihan</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
