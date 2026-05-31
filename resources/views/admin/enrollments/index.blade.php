@extends('layouts.admin')
@section('title','Peserta – '.$course->title)
@section('breadcrumb')
    <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600">Daftar Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('admin.courses.show', $course) }}" class="hover:text-blue-600">{{ $course->title }}</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">Peserta</span>
@endsection

@section('content')
<div class="p-6 space-y-5">
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-[22px] font-bold text-gray-900">Peserta Mata Kuliah</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $course->title }}</p>
        </div>
        <a href="{{ route('admin.enrollments.add-users') }}?course_id={{ $course->id }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.enrollments.index', $course) }}">
            <div class="flex gap-2.5">
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                           placeholder="Cari nama peserta..." class="input-field pl-9 text-sm">
                </div>
                <select name="status" class="select-field w-40 text-sm">
                    <option value="">Semua Status</option>
                    <option value="active"    {{ request('status')==='active'    ?'selected':'' }}>Aktif</option>
                    <option value="completed" {{ request('status')==='completed' ?'selected':'' }}>Selesai</option>
                    <option value="dropped"   {{ request('status')==='dropped'   ?'selected':'' }}>Keluar</option>
                </select>
                <button type="submit" class="btn-primary px-4 py-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Telusuri
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100">
            <span class="text-sm font-semibold text-gray-700">List Peserta ({{ $enrollments->total() }})</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="w-10 px-4 py-3"><input type="checkbox" class="rounded border-gray-300"></th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-4 py-3 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($enrollments as $enrollment)
                    <tr class="hover:bg-gray-50/60">
                        <td class="px-4 py-3.5"><input type="checkbox" class="rounded border-gray-300"></td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($enrollment->user->name ?? 'U', 0, 2)) }}
                                </div>
                                <span class="font-semibold text-gray-900 text-[13px]">{{ $enrollment->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-[13px] text-gray-600">{{ $enrollment->user->email ?? '-' }}</td>
                        <td class="px-4 py-3.5">
                            @php $s = $enrollment->status; @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $s==='active'?'bg-blue-100 text-blue-700':($s==='completed'?'bg-green-100 text-green-700':'bg-red-100 text-red-600') }}">
                                {{ ucfirst($s) }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-[12px] text-gray-600">
                            {{ \Carbon\Carbon::parse($enrollment->enrolled_at)->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-3.5">
                            <form action="{{ route('admin.enrollments.unenroll', [$course, $enrollment->user_id]) }}"
                                  method="POST" onsubmit="return confirm('Keluarkan peserta ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-medium hover:bg-red-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/></svg>
                                    Keluarkan
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-16 text-center text-sm text-gray-400">Belum ada peserta</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($enrollments->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-500">Menampilkan {{ $enrollments->firstItem() }} s.d. {{ $enrollments->lastItem() }} dari {{ $enrollments->total() }}</span>
            <div class="flex items-center gap-1">
                @foreach(range(max(1,$enrollments->currentPage()-2),min($enrollments->lastPage(),$enrollments->currentPage()+2)) as $p)
                    <a href="{{ $enrollments->url($p) }}" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-semibold border transition-colors {{ $p===$enrollments->currentPage()?'bg-blue-600 border-blue-600 text-white':'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ $p }}</a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
