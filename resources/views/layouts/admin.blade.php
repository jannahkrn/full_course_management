<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – Smart Exam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: { DEFAULT: '#2563EB', hover: '#1d4ed8', light: '#EFF6FF', border: '#BFDBFE' } },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:7px 10px; border-radius:8px; font-size:13px; font-weight:500; color:#4B5563; cursor:pointer; transition:background 0.15s,color 0.15s; text-decoration:none; white-space:nowrap; width:100%; text-align:left; background:none; border:none; }
        .nav-item:hover { background:#EFF6FF; color:#2563EB; }
        .nav-item.active { background:#EFF6FF; color:#2563EB; }
        .nav-item svg { flex-shrink:0; width:18px; height:18px; }
        .nav-sub-item { display:flex; align-items:center; padding:7px 10px 7px 38px; border-radius:8px; font-size:13px; font-weight:400; color:#6B7280; text-decoration:none; transition:background 0.15s,color 0.15s; }
        .nav-sub-item:hover { color:#2563EB; background:#EFF6FF; }
        .nav-sub-item.active { color:#2563EB; font-weight:500; background:#EFF6FF; }
        .nav-section-label { font-size:11px; font-weight:600; color:#9CA3AF; padding:12px 10px 5px; letter-spacing:0.02em; }
        .badge-ya    { display:inline-flex; align-items:center; padding:2px 10px; border-radius:999px; font-size:11px; font-weight:500; background:#DBEAFE; color:#1D4ED8; }
        .badge-tidak { display:inline-flex; align-items:center; padding:2px 10px; border-radius:999px; font-size:11px; font-weight:500; background:#FEE2E2; color:#DC2626; }
        .btn-primary   { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#2563EB; color:#fff; font-size:13px; font-weight:600; border-radius:8px; border:none; cursor:pointer; transition:background 0.15s; text-decoration:none; }
        .btn-primary:hover { background:#1D4ED8; }
        .btn-secondary { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#fff; color:#374151; font-size:13px; font-weight:500; border-radius:8px; border:1px solid #D1D5DB; cursor:pointer; transition:background 0.15s; text-decoration:none; }
        .btn-secondary:hover { background:#F9FAFB; }
        .input-field  { width:100%; padding:8px 12px; border:1px solid #D1D5DB; border-radius:8px; font-size:13px; outline:none; font-family:inherit; color:#111827; transition:border 0.15s,box-shadow 0.15s; }
        .input-field:focus { border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .select-field { width:100%; padding:8px 12px; border:1px solid #D1D5DB; border-radius:8px; font-size:13px; outline:none; font-family:inherit; color:#111827; background:#fff; transition:border 0.15s; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:16px; padding-right:36px; }
        .select-field:focus { border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarState: 'open' }">

<div class="flex h-screen overflow-hidden">

    <aside class="flex-shrink-0 bg-white border-r border-gray-200 flex flex-col z-30 transition-all duration-200"
           :style="sidebarState === 'open' ? 'width:240px; min-width:240px;' : 'width:70px; min-width:70px;'">

        <div class="flex items-center px-4 border-b border-gray-200 flex-shrink-0" style="height:56px;">
            <a href="{{ route('admin.courses.index') }}" class="flex items-center">
                <img src="{{ asset('assets/logo.png') }}" alt="Smart Exam" 
                     class="h-9 w-auto object-contain transition-all"
                     :class="sidebarState === 'open' ? '' : 'max-w-[40px]'">
            </a>
        </div>

        <button @click="sidebarState = (sidebarState === 'open' ? 'mini' : 'open')"
                class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition-colors flex-shrink-0 w-full text-left">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <span x-show="sidebarState === 'open'" class="text-[13px] font-medium text-gray-600 whitespace-nowrap">Menu</span>
        </button>

        <nav class="flex-1 overflow-y-auto overflow-x-hidden py-2" :class="sidebarState === 'open' ? 'px-3' : 'px-2 flex flex-col items-center'">

            <div x-show="sidebarState === 'open'" class="nav-section-label" style="padding-top:8px;">Pembelajaran</div>
            <div x-show="sidebarState === 'mini'" class="w-full border-t border-gray-100 my-2"></div>

            <a href="{{ route('admin.courses.index') }}"
               class="nav-item {{ request()->routeIs('admin.courses.*') && !request()->routeIs('admin.categories.*') && !request()->routeIs('admin.enrollments.*') ? 'active' : '' }}"
               :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Mata Kuliah Saya">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                </svg>
                <span x-show="sidebarState === 'open'" class="flex-1">Mata Kuliah Saya</span>
            </a>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Aktivitas Pembelajaran">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Aktivitas Pembelajaran</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Semua Aktivitas</a>
                </div>
            </div>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Evaluasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Evaluasi</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Daftar Evaluasi</a>
                </div>
            </div>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Laporan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Laporan</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Rekap Laporan</a>
                </div>
            </div>

            <div class="border-t border-gray-100 my-2 w-full"></div>

            <div x-show="sidebarState === 'open'" class="nav-section-label" style="padding-top:4px;">Administrasi Platform</div>

            <div x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Manajemen Pengguna">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4.03 3-9 3S3 13.66 3 12"/>
                        <path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Manajemen Pengguna</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="{{ route('admin.users.index') }}" class="nav-sub-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Daftar Pengguna</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }} }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item {{ request()->routeIs('admin.courses.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Manajemen Mata Kuliah">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Manajemen Mata Kuliah</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="{{ route('admin.courses.index') }}" class="nav-sub-item {{ request()->routeIs('admin.courses.*') && !request()->routeIs('admin.categories.*') ? 'active' : '' }}">Daftar Sesi</a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-sub-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategori Sesi</a>
                    </div>
            </div>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Pengaturan Platform">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.07 4.93a10 10 0 010 14.14M4.93 4.93a10 10 0 000 14.14"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Pengaturan Platform</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Konfigurasi</a>
                </div>
            </div>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Sistem">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Sistem</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Info Sistem</a>
                </div>
            </div>

            <div x-data="{ open: false }" class="w-full">
                <button @click="sidebarState === 'open' ? open = !open : sidebarState = 'open'" class="nav-item" :class="sidebarState === 'mini' ? 'justify-center' : ''" title="Keamanan & Privasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                    <span x-show="sidebarState === 'open'" class="flex-1">Keamanan & Privasi</span>
                    <svg x-show="sidebarState === 'open'" class="w-3.5 h-3.5 flex-shrink-0 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open && sidebarState === 'open'" x-cloak class="space-y-0.5">
                    <a href="#" class="nav-sub-item">Pengaturan Keamanan</a>
                </div>
            </div>

        </nav>
    </aside>

    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        <header class="bg-white border-b border-gray-200 flex items-center justify-between px-5 flex-shrink-0 z-40" style="height:56px;">
            
            <button @click="sidebarState = (sidebarState === 'open' ? 'mini' : 'open')"
                    class="flex items-center gap-2 text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span x-show="sidebarState === 'mini'" class="text-sm font-semibold text-gray-700">Menu</span>
            </button>

            <div class="flex items-center gap-3">
                <button class="relative w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center ring-2 ring-white leading-none">99+</span>
                </button>

                <div class="w-px h-6 bg-gray-200"></div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2.5 py-1.5 px-1 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if(auth()->user() && auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                                </svg>
                            @endif
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[13px] font-semibold text-gray-900 leading-tight">{{ auth()->user()->name ?? 'User Name' }}</div>
                            <div class="mt-0.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold border bg-yellow-50 text-yellow-700 border-yellow-200">Admin</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-1 w-52 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                        <div class="px-4 py-2.5 border-b border-gray-100">
                            <div class="text-[13px] font-semibold text-gray-900">{{ auth()->user()->name ?? 'User Name' }}</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="mx-6 mt-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="mx-6 mt-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">{{ session('error') }}</span>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-200 px-6 py-3 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('assets/logo.png') }}" alt="Smart Exam" class="h-7 w-auto object-contain flex-shrink-0">
                <span class="text-xs text-gray-500">© 2026 Smart Exam | All rights reserved.</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="#" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Cara Kerja</a>
                <a href="#" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Pusat Bantuan</a>
                <a href="#" class="text-xs text-gray-500 hover:text-blue-600 transition-colors">Hubungi Kami</a>
            </div>
        </footer>
    </div>
</div>

@stack('scripts')
</body>
</html>