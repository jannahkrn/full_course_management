@extends('layouts.admin')

@section('title', 'Tambah Pengguna ke Mata Kuliah')

@section('breadcrumb')
    <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-600">Daftar Mata Kuliah</a>
    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-gray-900 font-medium">Tambah Pengguna ke Mata Kuliah</span>
@endsection

@section('content')
<div class="p-6 space-y-5">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Tambah Pengguna ke Mata Kuliah</h1>
        </div>
    </div>

    @if($errors->any())
    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6"
         x-data="{
            selectedUsers: {{ json_encode(old('user_ids', [])) }},
            selectedCourses: {{ json_encode(old('course_ids', request('course_id') ? [request('course_id')] : [])) }},
            users: {{ $users->map(fn($u) => ['id' => (string)$u->id, 'name' => $u->name, 'email' => $u->email])->toJson() }},
            courses: {{ $courses->map(fn($c) => ['id' => (string)$c->id, 'title' => $c->title])->toJson() }},
            userSearch: '',
            courseSearch: '',

            get filteredUsers() {
                const q = this.userSearch.toLowerCase();
                return this.users.filter(u =>
                    u.name.toLowerCase().includes(q) || u.email.toLowerCase().includes(q)
                );
            },
            get filteredCourses() {
                const q = this.courseSearch.toLowerCase();
                return this.courses.filter(c => c.title.toLowerCase().includes(q));
            },
            get selectedUserObjs() { return this.users.filter(u => this.selectedUsers.includes(u.id)) },
            get selectedCourseObjs() { return this.courses.filter(c => this.selectedCourses.includes(c.id)) },

            toggleUser(id) {
                const sid = String(id);
                if (this.selectedUsers.includes(sid)) {
                    this.selectedUsers = this.selectedUsers.filter(s => s !== sid);
                } else {
                    this.selectedUsers.push(sid);
                }
            },
            toggleCourse(id) {
                const sid = String(id);
                if (this.selectedCourses.includes(sid)) {
                    this.selectedCourses = this.selectedCourses.filter(s => s !== sid);
                } else {
                    this.selectedCourses.push(sid);
                }
            },
            removeUser(id) { this.selectedUsers = this.selectedUsers.filter(s => s !== String(id)) },
            removeCourse(id) { this.selectedCourses = this.selectedCourses.filter(s => s !== String(id)) },
         }">

        <form action="{{ route('admin.enrollments.bulk') }}" method="POST">
            @csrf

            {{-- Hidden inputs --}}
            <template x-for="id in selectedUsers" :key="'u'+id">
                <input type="hidden" name="user_ids[]" :value="id">
            </template>
            <template x-for="id in selectedCourses" :key="'c'+id">
                <input type="hidden" name="course_ids[]" :value="id">
            </template>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                {{-- ── Pilih Pengguna ── --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Pengguna</label>

                    {{-- Selected chips --}}
                    <div class="flex flex-wrap gap-2 min-h-[2rem] mb-3">
                        <template x-for="u in selectedUserObjs" :key="u.id">
                            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-full border border-blue-200">
                                <span x-text="u.name"></span>
                                <button type="button" @click="removeUser(u.id)"
                                        class="w-4 h-4 flex items-center justify-center bg-blue-200 hover:bg-blue-300 rounded-full text-blue-700 transition-colors">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        </template>
                        <span x-show="selectedUsers.length === 0" class="text-xs text-gray-400 self-center">Belum ada pengguna dipilih</span>
                    </div>

                    {{-- Search + List --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="p-2 border-b border-gray-100">
                            <div class="relative">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="userSearch" placeholder="Cari pengguna..."
                                       class="w-full pl-7 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="u in filteredUsers" :key="u.id">
                                <label class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                                    <input type="checkbox"
                                           :checked="selectedUsers.includes(u.id)"
                                           @change="toggleUser(u.id)"
                                           class="rounded border-gray-300 text-blue-600">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-800 truncate" x-text="u.name"></div>
                                        <div class="text-xs text-gray-400 truncate" x-text="u.email"></div>
                                    </div>
                                </label>
                            </template>
                            <div x-show="filteredUsers.length === 0" class="px-3 py-6 text-center text-xs text-gray-400">
                                Tidak ada hasil
                            </div>
                        </div>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400" x-text="`${selectedUsers.length} pengguna dipilih`"></p>
                </div>

                {{-- Arrow icon (center, desktop) --}}
                <div class="hidden lg:flex items-start justify-center pt-10 -mx-4">
                    {{-- just layout spacer, actual arrow shown in between --}}
                </div>

                {{-- ── Pilih Mata Kuliah ── --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Mata Kuliah</label>

                    {{-- Selected chips --}}
                    <div class="flex flex-wrap gap-2 min-h-[2rem] mb-3">
                        <template x-for="c in selectedCourseObjs" :key="c.id">
                            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1 bg-green-50 text-green-700 text-xs font-medium rounded-full border border-green-200">
                                <span x-text="c.title"></span>
                                <button type="button" @click="removeCourse(c.id)"
                                        class="w-4 h-4 flex items-center justify-center bg-green-200 hover:bg-green-300 rounded-full text-green-700 transition-colors">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </span>
                        </template>
                        <span x-show="selectedCourses.length === 0" class="text-xs text-gray-400 self-center">Belum ada mata kuliah dipilih</span>
                    </div>

                    {{-- Search + List --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <div class="p-2 border-b border-gray-100">
                            <div class="relative">
                                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="courseSearch" placeholder="Cari mata kuliah..."
                                       class="w-full pl-7 pr-3 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <template x-for="c in filteredCourses" :key="c.id">
                                <label class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-0">
                                    <input type="checkbox"
                                           :checked="selectedCourses.includes(c.id)"
                                           @change="toggleCourse(c.id)"
                                           class="rounded border-gray-300 text-blue-600">
                                    <div class="text-sm font-medium text-gray-800 truncate" x-text="c.title"></div>
                                </label>
                            </template>
                            <div x-show="filteredCourses.length === 0" class="px-3 py-6 text-center text-xs text-gray-400">
                                Tidak ada hasil
                            </div>
                        </div>
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400" x-text="`${selectedCourses.length} mata kuliah dipilih`"></p>
                </div>
            </div>

            {{-- Submit --}}
            <div class="mt-8 pt-5 border-t border-gray-100 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    <span x-text="selectedUsers.length"></span> pengguna →
                    <span x-text="selectedCourses.length"></span> mata kuliah
                    (<span x-text="selectedUsers.length * selectedCourses.length"></span> pendaftaran)
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.courses.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit"
                            :disabled="selectedUsers.length === 0 || selectedCourses.length === 0"
                            :class="selectedUsers.length === 0 || selectedCourses.length === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                            class="btn-primary">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah ke Mata Kuliah
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection