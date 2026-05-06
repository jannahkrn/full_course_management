@extends('layouts.app')

@section('breadcrumb')
    <span>Administrasi Platform > Manajemen Mata Kuliah</span>
@endsection

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">Daftar Mata Kuliah</h2>
        <a href="{{ route('admin.courses.create') }}">
            <button style="background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                + Tambah Mata Kuliah
            </button>
        </a>
    </div>

    @if(session('success'))
        <div style="padding: 10px; background: #d4edda; color: #155724; margin-bottom: 20px; border-radius: 4px;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Navbar/Tab Mode (Gaya Pills sesuai UI) -->
    <div style="display: flex; gap: 5px; margin-bottom: 20px; background: #f0f0f0; padding: 5px; border-radius: 8px; width: fit-content;">
        <a href="{{ route('admin.courses.index', ['view' => 'standar']) }}" 
           style="text-decoration: none; padding: 8px 20px; border-radius: 6px; {{ $view == 'standar' ? 'background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #007bff;' : 'color: #666;' }}">
           List Standar
        </a>
        <a href="{{ route('admin.courses.index', ['view' => 'manajemen']) }}" 
           style="text-decoration: none; padding: 8px 20px; border-radius: 6px; {{ $view == 'manajemen' ? 'background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #007bff;' : 'color: #666;' }}">
           List Manajemen
        </a>
    </div>

    <!-- Filter Section -->
    <div style="background: #fdfdfd; padding: 15px; border: 1px solid #eee; border-radius: 8px; margin-bottom: 20px;">
        <form action="{{ route('admin.courses.index') }}" method="GET" style="display: flex; gap: 10px;">
            <input type="hidden" name="view" value="{{ $view }}">
            <input type="text" name="keyword" value="{{ $filters['keyword'] ?? '' }}" placeholder="Cari judul atau kode..." style="flex-grow: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
            
            <select name="category_id" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ ($filters['category_id'] ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="language" style="padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="">Semua Bahasa</option>
                <option value="id" {{ ($filters['language'] ?? '') == 'id' ? 'selected' : '' }}>Indonesia</option>
                <option value="en" {{ ($filters['language'] ?? '') == 'en' ? 'selected' : '' }}>Inggris</option>
            </select>

            <button type="submit" style="background: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 4px; cursor: pointer;">Telusuri</button>
            <a href="{{ route('admin.courses.index', ['view' => $view]) }}" style="padding: 8px; color: #666; text-decoration: none;">Reset</a>
        </form>
    </div>

    <!-- Table -->
    <table style="width: 100%; border-collapse: collapse; background: white;">
        <thead>
            <tr style="text-align: left; border-bottom: 2px solid #eee;">
                @if($view === 'manajemen')
                    <th style="padding: 12px;">Kode & Judul</th>
                    <th style="padding: 12px;">Tipe Akses</th>
                    <th style="padding: 12px;">Pendaftar</th>
                    <th style="padding: 12px;">Status</th>
                @else
                    <th style="padding: 12px;">Judul</th>
                    <th style="padding: 12px;">Bahasa</th>
                    <th style="padding: 12px;">Kategori</th>
                    <th style="padding: 12px; text-align: center;">Terdaftar Diizinkan</th>
                    <th style="padding: 12px; text-align: center;">Tidak Terdaftar Diizinkan</th>
                @endif
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr style="border-bottom: 1px solid #eee;">
                @if($view === 'manajemen')
                    <td style="padding: 12px;"><strong>[{{ $course->code }}]</strong> {{ $course->title }}</td>
                    <td style="padding: 12px;">{{ ucfirst($course->access_type) }}</td>
                    <td style="padding: 12px;">{{ $course->enrollments_count ?? 0 }} Pengguna</td>
                    <td style="padding: 12px;">{{ $course->is_active ? 'Aktif' : 'Non-Aktif' }}</td>
                @else
                    <td style="padding: 12px;">
                        <strong>{{ $course->title }}</strong><br>
                        <small style="color: #888;">{{ $course->code }}</small>
                    </td>
                    <td style="padding: 12px;">{{ $course->language == 'id' ? 'Indonesia' : 'Inggris' }}</td>
                    <td style="padding: 12px;">{{ $course->category->name ?? '-' }}</td>
                    <!-- Status Terdaftar (is_allowed biasanya merujuk ke pendaftaran mandiri user login) -->
            <td style="padding: 12px; text-align: center;">
                {!! $course->is_allowed 
                    ? '<span style="color: green;">✔</span>' 
                    : '<span style="color: red;">✘</span>' !!}
            </td>
            
            <!-- Status Tidak Terdaftar (Tamu/Public) -->
            <td style="padding: 12px; text-align: center;">
                {!! $course->access_type === 'open' 
                    ? '<span style="color: green;">✔</span>' 
                    : '<span style="color: red;">✘</span>' !!}
            </td>
        @endif

                <!-- DROPDOWN AKSI SESUAI UI -->
                <td style="padding: 12px; text-align: center;">
                    <select onchange="handleAction(this, '{{ $course->id }}')" style="padding: 5px; border-radius: 4px; border: 1px solid #ddd; cursor: pointer;">
                        <option value="">Pilih Aksi</option>
                        <option value="show">Situs Mata Kuliah</option>
                        <option value="search">Penelusuran</option>
                        <option value="edit">Ubah Mata Kuliah</option>
                        <option value="duplicate">Buat Cadangan</option>
                        <option value="delete" style="color: red;">Hapus Mata Kuliah</option>
                    </select>
                    
                    <!-- Hidden Forms for Actions -->
                    <form id="duplicate-form-{{ $course->id }}" action="{{ route('admin.courses.duplicate', $course->id) }}" method="POST" style="display: none;">@csrf</form>
                    <form id="delete-form-{{ $course->id }}" action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display: none;">@csrf @method('DELETE')</form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $courses->appends(request()->query())->links() }}
    </div>

    <script>
        function handleAction(select, id) {
            const action = select.value;
            if (action === 'show') {
                window.location.href = `/admin/courses/${id}`;
            }else if (action === 'search') {
                window.location.href = `/admin/courses/${id}/search`;
            }else if (action === 'edit') {
                window.location.href = `/admin/courses/${id}/edit`;
            } else if (action === 'duplicate') {
                document.getElementById(`duplicate-form-${id}`).submit();
            } else if (action === 'delete') {
                if (confirm('Hapus mata kuliah ini?')) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            }
            select.value = ""; // reset dropdown
        }
    </script>
@endsection