@extends('layouts.admin')

@section('title', $course->title)

@section('breadcrumb')
    <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600">Daftar Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-900 font-medium">{{ $course->title }}</span>
@endsection

@section('content')
<div class="p-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-xl font-bold text-gray-900">{{ $course->title }}</h1>
                @if($course->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ $course->category->name }}
                    </span>
                @endif
                @if($course->is_active)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">Aktif</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Nonaktif</span>
                @endif
            </div>
            @if($course->code)
                <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $course->code }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2">
            {{-- Publish / Unpublish --}}
            @if($course->is_active)
                <form action="{{ route('admin.courses.unpublish', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-secondary text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        Unpublish
                    </button>
                </form>
            @else
                <form action="{{ route('admin.courses.publish', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-secondary text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Publish
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.courses.edit', $course) }}" class="btn-primary text-xs">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Ubah
            </a>

            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-red-50 text-red-600 text-xs font-medium rounded-lg border border-red-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Info Umum --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Informasi Umum</h2>
                <dl class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Bahasa</dt>
                        <dd class="text-gray-700 font-medium">{{ $course->language === 'id' ? 'Bahasa Indonesia' : 'English' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Akses</dt>
                        <dd class="text-gray-700 font-medium capitalize">{{ $course->access_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Departemen</dt>
                        <dd class="text-gray-700 font-medium">{{ $course->department ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Terdaftar</dt>
                        <dd>
                            @if($course->is_registered)
                                <span class="badge-ya">Ya</span>
                            @else
                                <span class="badge-tidak">Tidak</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Diizinkan</dt>
                        <dd>
                            @if($course->is_allowed)
                                <span class="badge-ya">Ya</span>
                            @else
                                <span class="badge-tidak">Tidak</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Berhenti Berlangganan</dt>
                        <dd>
                            @if($course->allow_unsubscribe)
                                <span class="badge-ya">Diizinkan</span>
                            @else
                                <span class="badge-tidak">Tidak Diizinkan</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Penyimpanan</dt>
                        <dd class="text-gray-700 font-medium">{{ $course->storage_limit_mb ? $course->storage_limit_mb . ' MB' : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 mb-0.5">Dibuat</dt>
                        <dd class="text-gray-700 font-medium">{{ $course->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>

                @if($course->tags && count($course->tags))
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <dt class="text-xs text-gray-400 mb-2">Tags</dt>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($course->tags as $tag)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Guru --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Guru</h2>
                @if($course->teachers->count())
                    <div class="space-y-2">
                        @foreach($course->teachers as $teacher)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                {{ strtoupper(substr($teacher->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $teacher->name }}</div>
                                <div class="text-xs text-gray-400">{{ $teacher->email }}</div>
                            </div>
                            @if($teacher->pivot->role === 'primary')
                                <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">Primary</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">Belum ada guru</p>
                @endif
            </div>

            {{-- Sesi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-700">Sesi ({{ $course->sessions->count() }})</h2>
                </div>
                @if($course->sessions->count())
                    <div class="space-y-2">
                        @foreach($course->sessions as $session)
                        <div class="flex items-center gap-3 p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                            <div class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-xs font-semibold">
                                {{ $session->order + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-800 truncate">{{ $session->title }}</div>
                                <div class="text-xs text-gray-400">
                                    {{ $session->materials->count() }} Materi · {{ $session->exercises->count() }} Latihan
                                </div>
                            </div>
                            @if(!$session->is_active)
                                <span class="text-xs text-gray-400">Nonaktif</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center gap-2 py-8 text-gray-400">
                        <svg class="w-10 h-10 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="text-sm">Belum ada sesi</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Stats + Enrollment --}}
        <div class="space-y-5">

            {{-- Stats --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Statistik</h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Peserta Aktif</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $course->enrollments->where('status', 'active')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Selesai</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $course->enrollments->where('status', 'completed')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Total Sesi</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $course->sessions->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <h2 class="text-sm font-semibold text-gray-700 mb-4">Aksi Cepat</h2>
                <div class="space-y-2">
                    <a href="{{ route('admin.enrollments.add-users') }}?course_id={{ $course->id }}"
                       class="w-full btn-secondary justify-center text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        Tambah Pengguna
                    </a>
                    <form action="{{ route('admin.courses.duplicate', $course) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full btn-secondary justify-center text-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            Buat Cadangan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Recent Enrollments --}}
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-700">Peserta Terbaru</h2>
                    <a href="{{ route('admin.enrollments.index', $course) }}" class="text-xs text-blue-600 hover:underline">Lihat semua</a>
                </div>
                @php $recent = $course->enrollments->sortByDesc('enrolled_at')->take(5) @endphp
                @if($recent->count())
                    <div class="space-y-2">
                        @foreach($recent as $enroll)
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-gray-200 rounded-full flex items-center justify-center text-gray-600 text-xs font-semibold flex-shrink-0">
                                {{ strtoupper(substr($enroll->user->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-medium text-gray-800 truncate">{{ $enroll->user->name ?? '-' }}</div>
                                <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($enroll->enrolled_at)->format('d M Y') }}</div>
                            </div>
                            <span class="text-xs px-2 py-0.5 rounded-full
                                {{ $enroll->status === 'active' ? 'bg-blue-50 text-blue-600' : ($enroll->status === 'completed' ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500') }}">
                                {{ ucfirst($enroll->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada peserta</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection