@extends('layouts.app')
@section('title', $course->title)
@section('breadcrumb')
    <a href="{{ route('teacher.courses.index') }}" class="hover:text-blue-600">Katalog Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">{{ $course->title }}</span>
@endsection

@section('sidebar-nav')
    <div class="nav-section-label">Mata Kuliah</div>
    <a href="{{ route('teacher.courses.index') }}" class="nav-item">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        <span x-show="sidebarOpen" x-cloak>Mata Kuliah Saya</span>
    </a>
    <a href="{{ route('teacher.courses.index') }}" class="nav-item active">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        <span x-show="sidebarOpen" x-cloak>Katalog</span>
    </a>
    <div class="nav-section-label" x-show="sidebarOpen" x-cloak>Pengelolaan</div>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 4v16m8-8H4"/></svg><span x-show="sidebarOpen" x-cloak>Buat Mata Kuliah</span></a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg><span x-show="sidebarOpen" x-cloak>Kelola Sesi</span></a>
    <div class="nav-section-label" x-show="sidebarOpen" x-cloak>Aktivitas</div>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg><span x-show="sidebarOpen" x-cloak>Terakhir Dikunjungi</span></a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg><span x-show="sidebarOpen" x-cloak>Riwayat</span></a>
    <a href="#" class="nav-item"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg><span x-show="sidebarOpen" x-cloak>Laporan Waktu</span></a>
@endsection

@section('content')
<div class="p-6 space-y-5">
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-[22px] font-bold text-gray-900">{{ $course->title }}</h1>
                @if($course->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">{{ $course->category->name }}</span>
                @endif
            </div>
            <p class="text-sm text-gray-500">{{ $course->teachers->pluck('name')->join(', ') }} · {{ $course->active_enrollments_count ?? 0 }} Mahasiswa</p>
        </div>
        <a href="{{ route('teacher.courses.index') }}" class="btn-secondary text-sm">← Kembali</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ tab: 'materi' }">
        <div class="flex border-b border-gray-100">
            <button @click="tab='materi'" :class="tab==='materi'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3.5 text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Materi
                <span class="text-xs text-gray-400">Description</span>
            </button>
            <button @click="tab='latihan'" :class="tab==='latihan'?'border-b-2 border-blue-600 text-blue-600':'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3.5 text-sm font-semibold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Latihan
                <span class="text-xs text-gray-400">Description</span>
            </button>
        </div>

        <div class="p-6">
            <div x-show="tab==='materi'">
                @if($course->sessions->count())
                    <div class="space-y-3">
                        @foreach($course->sessions as $session)
                        <div class="border border-gray-100 rounded-xl p-4 hover:bg-gray-50">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs font-bold">{{ $session->order + 1 }}</div>
                                <span class="font-semibold text-sm text-gray-900">{{ $session->title }}</span>
                            </div>
                            @if($session->materials->count())
                            <div class="ml-10 space-y-1">
                                @foreach($session->materials as $mat)
                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        {{ $mat->title }}
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center gap-3 py-12 text-gray-400">
                        <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm">Belum ada materi</span>
                    </div>
                @endif
            </div>
            <div x-show="tab==='latihan'">
                @php $exercises = $course->sessions->flatMap->exercises; @endphp
                @if($exercises->count())
                    <div class="space-y-2">
                        @foreach($exercises as $ex)
                        <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:bg-gray-50">
                            <div>
                                <p class="font-semibold text-sm text-gray-900">{{ $ex->title }}</p>
                                <p class="text-xs text-gray-500 capitalize mt-0.5">{{ $ex->type }} · {{ $ex->duration_minutes ?? '-' }} menit</p>
                            </div>
                            <span class="text-xs font-medium text-gray-500">Skor max: {{ $ex->max_score }}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center gap-3 py-12 text-gray-400">
                        <svg class="w-12 h-12 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                        <span class="text-sm">Belum ada latihan</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
