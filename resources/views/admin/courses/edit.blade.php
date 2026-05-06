@extends('layouts.admin')

@section('title', 'Ubah Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600">Daftar Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-900 font-medium">Ubah Mata Kuliah</span>
@endsection

@section('content')
<div class="p-6">
    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Ubah Mata Kuliah</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $course->title }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.courses.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <div class="grid grid-cols-1 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}"
                               placeholder="Masukkan judul"
                               class="input-field @error('title') border-red-400 @enderror">
                        @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kode</label>
                        <input type="text" name="code" value="{{ old('code', $course->code) }}"
                               placeholder="Masukkan kode"
                               class="input-field @error('code') border-red-400 @enderror">
                        <p class="mt-1 text-xs text-gray-400">Hanya huruf (a-z) dan angka (0-9)</p>
                        @error('code')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Kategori</label>
                        <select name="category_id" class="select-field">
                            <option value="">Pilih kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Guru --}}
                    <div x-data="{
                        selected: {{ json_encode(old('teacher_ids', $course->teachers->pluck('id')->map(fn($id) => (string)$id)->toArray())) }},
                        teachers: {{ $teachers->map(fn($t) => ['id' => $t->id, 'name' => $t->name])->toJson() }},
                        get selectedTeachers() { return this.teachers.filter(t => this.selected.includes(String(t.id))) },
                        toggle(id) {
                            const sid = String(id);
                            if (this.selected.includes(sid)) {
                                this.selected = this.selected.filter(s => s !== sid);
                            } else {
                                this.selected.push(sid);
                            }
                        },
                        remove(id) { this.selected = this.selected.filter(s => s !== String(id)) }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Guru</label>
                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="teacher_ids[]" :value="id">
                        </template>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="t in selectedTeachers" :key="t.id">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-200">
                                    <span x-text="t.name"></span>
                                    <button type="button" @click="remove(t.id)" class="text-blue-400 hover:text-blue-700">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <select @change="toggle($event.target.value); $event.target.value=''" class="select-field">
                            <option value="">+ Tambah guru...</option>
                            <template x-for="t in teachers" :key="t.id">
                                <option :value="t.id" :disabled="selected.includes(String(t.id))" x-text="t.name"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Departemen</label>
                        <input type="text" name="department" value="{{ old('department', $course->department) }}"
                               placeholder="Masukkan departemen" class="input-field">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Departemen</label>
                        <input type="text" name="department_url" value="{{ old('department_url', $course->department_url) }}"
                               placeholder="Masukkan url departemen" class="input-field">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Bahasa</label>
                        <select name="language" class="select-field">
                            <option value="">Pilih bahasa...</option>
                            <option value="en" {{ old('language', $course->language) === 'en' ? 'selected' : '' }}>English</option>
                            <option value="id" {{ old('language', $course->language) === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Templat Mata Kuliah</label>
                        <select name="template_course_id" class="select-field">
                            <option value="">Pilih templat mata kuliah...</option>
                            @foreach($templateCourses as $tc)
                                <option value="{{ $tc->id }}"
                                    {{ old('template_course_id', $course->template_course_id) == $tc->id ? 'selected' : '' }}>
                                    {{ $tc->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-400">Pilih mata kuliah sebagai templat – isi dengan konten demo</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Akses Mata Kuliah</label>
                        <select name="access_type" class="select-field">
                            <option value="">Pilih akses mata kuliah...</option>
                            <option value="public"     {{ old('access_type', $course->access_type) === 'public'     ? 'selected' : '' }}>Public</option>
                            <option value="private"    {{ old('access_type', $course->access_type) === 'private'    ? 'selected' : '' }}>Private</option>
                            <option value="restricted" {{ old('access_type', $course->access_type) === 'restricted' ? 'selected' : '' }}>Restricted</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Langganan</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="subscription_type" value="allowed"
                                       {{ old('subscription_type', $course->subscription_type) === 'allowed' ? 'checked' : '' }}
                                       class="text-blue-600">
                                <span class="text-sm text-gray-700">Diizinkan</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="subscription_type" value="teacher_only"
                                       {{ old('subscription_type', $course->subscription_type) === 'teacher_only' ? 'checked' : '' }}
                                       class="text-blue-600">
                                <span class="text-sm text-gray-700">Fungsi ini hanya tersedia untuk guru</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berhenti Berlangganan</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="allow_unsubscribe" value="1"
                                       {{ old('allow_unsubscribe', $course->allow_unsubscribe ? '1' : '0') === '1' ? 'checked' : '' }}
                                       class="text-blue-600">
                                <span class="text-sm text-gray-700">Pengguna diperbolehkan berhenti langganan mata kuliah</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="allow_unsubscribe" value="0"
                                       {{ old('allow_unsubscribe', $course->allow_unsubscribe ? '1' : '0') === '0' ? 'checked' : '' }}
                                       class="text-blue-600">
                                <span class="text-sm text-gray-700">Pengguna tidak diperbolehkan berhenti langganan mata kuliah</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Ruang Penyimpanan</label>
                        <div class="relative">
                            <input type="number" name="storage_limit_mb"
                                   value="{{ old('storage_limit_mb', $course->storage_limit_mb) }}"
                                   placeholder="Masukkan jumlah penyimpanan"
                                   class="input-field pr-12">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">MB</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah Khusus</label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_special" value="1"
                                   {{ old('is_special', $course->is_special) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600">
                            <span class="text-sm text-gray-700">Ya</span>
                        </label>
                    </div>

                    {{-- Tags --}}
                    <div x-data="{
                        tags: {{ json_encode(old('tags', $course->tags ?? [])) }},
                        input: '',
                        add() {
                            const t = this.input.trim();
                            if (t && !this.tags.includes(t)) { this.tags.push(t); }
                            this.input = '';
                        },
                        remove(i) { this.tags.splice(i, 1); }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tags</label>
                        <template x-for="(tag, i) in tags" :key="i">
                            <input type="hidden" name="tags[]" :value="tag">
                        </template>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(tag, i) in tags" :key="i">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="remove(i)" class="text-gray-400 hover:text-gray-700">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <input type="text" x-model="input"
                               @keydown.enter.prevent="add()"
                               @keydown.comma.prevent="add()"
                               placeholder="Mulai mengetik, lalu tekan Enter untuk menambah tag"
                               class="input-field">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">URL Video</label>
                        <input type="text" name="video_url" value="{{ old('video_url', $course->video_url) }}"
                               placeholder="Masukkan url video" class="input-field">
                    </div>

                </div>
            </div>

            <div class="flex justify-end gap-2 pb-2">
                <a href="{{ route('admin.courses.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection