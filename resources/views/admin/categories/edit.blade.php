@extends('layouts.admin')
@section('title','Ubah Kategori')
@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="hover:text-blue-600">Kategori Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-800 font-medium text-sm">Ubah Kategori</span>
@endsection
@section('content')
<div class="p-6 max-w-xl">
    <h1 class="text-xl font-bold text-gray-900 mb-6">Ubah Kategori</h1>
    @if($errors->any())
    <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" placeholder="Masukkan nama kategori"
                       class="input-field @error('name') border-red-400 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Masukkan deskripsi kategori"
                          class="input-field resize-none">{{ old('description', $category->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700">Aktif</span>
                </label>
            </div>
            <div class="pt-1">
                <p class="text-xs text-gray-400">Digunakan oleh {{ $category->courses_count }} mata kuliah</p>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
