<!-- resources/views/admin/courses/index.blade.php -->
@extends('layouts.app')

@section('breadcrumb')
    <span>Administrasi Platform > Manajemen Mata Kuliah</span>
@endsection

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1>Daftar Mata Kuliah</h1>
        <a href="{{ route('admin.courses.create') }}">
            <button>+ Tambah Mata Kuliah</button>
        </a>
    </div>

    <!-- Filter Simple -->
    <div style="margin: 20px 0; border: 1px solid #eee; padding: 10px;">
        <input type="text" placeholder="Masukkan nama mata kuliah...">
        <button>Telusuri</button>
    </div>

    <!-- Tabel Data -->
    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Bahasa</th>
                <th>Kategori</th>
                <th>Tendaftar Diizinkan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
            <tr>
                <td>{{ $course->title }}</td>
                <td>{{ $course->language ?? 'Indonesia' }}</td>
                <td>{{ $course->category->name ?? '-' }}</td>
                <td>{{ $course->is_published ? 'Ya' : 'Tidak' }}</td>
                <td>
                    <a href="{{ route('admin.courses.edit', $course->id) }}">Ubah</a> |
                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada data mata kuliah.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection