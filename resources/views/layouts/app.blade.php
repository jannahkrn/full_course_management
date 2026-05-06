<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Smart Exam')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .nav-item { display:flex; align-items:center; gap:10px; padding:7px 10px; border-radius:8px; font-size:13px; font-weight:500; color:#4B5563; cursor:pointer; transition:background 0.15s,color 0.15s; text-decoration:none; }
        .nav-item:hover { background:#EFF6FF; color:#2563EB; }
        .nav-item.active { background:#EFF6FF; color:#2563EB; }
        .nav-item svg { flex-shrink:0; width:18px; height:18px; }
        .nav-sub-item { display:flex; align-items:center; padding:6px 10px 6px 20px; border-radius:8px; font-size:12.5px; font-weight:500; color:#6B7280; text-decoration:none; transition:background 0.15s,color 0.15s; }
        .nav-sub-item:hover { background:#EFF6FF; color:#2563EB; }
        .nav-sub-item.active { background:#EFF6FF; color:#2563EB; }
        .nav-section-label { font-size:10.5px; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; color:#9CA3AF; padding:12px 10px 4px; }
        .btn-primary { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#2563EB; color:#fff; font-size:13px; font-weight:600; border-radius:8px; cursor:pointer; transition:background 0.15s; text-decoration:none; border:none; }
        .btn-primary:hover { background:#1D4ED8; }
        .btn-secondary { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; background:#fff; color:#374151; font-size:13px; font-weight:500; border-radius:8px; border:1px solid #D1D5DB; cursor:pointer; transition:background 0.15s; text-decoration:none; }
        .btn-secondary:hover { background:#F9FAFB; }
        .input-field { width:100%; padding:8px 12px; border:1px solid #D1D5DB; border-radius:8px; font-size:13px; outline:none; font-family:inherit; color:#111827; transition:border 0.15s,box-shadow 0.15s; }
        .input-field:focus { border-color:#2563EB; box-shadow:0 0 0 3px rgba(37,99,235,0.1); }
        .select-field { width:100%; padding:8px 12px; border:1px solid #D1D5DB; border-radius:8px; font-size:13px; outline:none; font-family:inherit; color:#111827; background:#fff; transition:border 0.15s; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 10px center; background-size:16px; padding-right:36px; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside class="flex-shrink-0 bg-white border-r border-gray-200 flex flex-col transition-all duration-300 z-30"
           :class="sidebarOpen ? 'w-[220px]' : 'w-[52px]'">

        {{-- Logo --}}
        <div class="flex items-center gap-2.5 px-3.5 py-4 border-b border-gray-100 overflow-hidden">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L4 7V13C4 17.418 7.582 21 12 21C16.418 21 20 17.418 20 13V7L12 3Z" fill="white" fill-opacity="0.3"/>
                    <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div x-show="sidebarOpen" x-cloak>
                <span class="text-[15px] font-bold text-gray-900 tracking-tight">Smart<span class="text-blue-600">Exam</span></span>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-2.5 py-3 space-y-0.5 overflow-x-hidden">
            @yield('sidebar-nav')
        </nav>

        {{-- Collapse --}}
        <div class="p-2.5 border-t border-gray-100">
            <button @click="sidebarOpen = !sidebarOpen" class="nav-item w-full justify-center" style="padding:8px;">
                <svg class="transition-transform" :class="sidebarOpen ? '' : 'rotate-180'"
                     width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                </svg>
            </button>
        </div>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header class="bg-white border-b border-gray-200 flex items-center justify-between px-6 h-14 flex-shrink-0">
            <nav class="flex items-center gap-1.5 text-sm text-gray-500">
                <a href="{{ url('/') }}" class="hover:text-blue-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Pengelolaan Mata Kuliah
                </a>
                @hasSection('breadcrumb')
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    @yield('breadcrumb')
                @endif
            </nav>

            <div class="flex items-center gap-2">
                <button class="relative w-9 h-9 flex items-center justify-center rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white"></span>
                </button>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg hover:bg-gray-50">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-blue-100 flex-shrink-0">
                            @if(auth()->user()->avatar)
                                <img src="{{ Storage::url(auth()->user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div class="text-left hidden sm:block">
                            <div class="text-[13px] font-semibold text-gray-900 leading-tight">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="text-[11px] text-gray-400 capitalize leading-tight">{{ auth()->user()->role ?? '' }}</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false" x-cloak
                         class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                        <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profil Saya
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
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

        {{-- FLASH --}}
        @if(session('success'))
        <div x-data="{ show:true }" x-show="show" x-cloak
             class="mx-6 mt-4 flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show=false"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @endif
        @if(session('error'))
        <div x-data="{ show:true }" x-show="show" x-cloak
             class="mx-6 mt-4 flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 text-sm">
            <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="flex-1">{{ session('error') }}</span>
            <button @click="show=false"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
        </div>
        @endif

        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>

        <footer class="bg-white border-t border-gray-100 px-6 py-3 flex items-center justify-between text-xs text-gray-400 flex-shrink-0">
            <div class="flex items-center gap-2">
                <div class="w-4 h-4 bg-blue-600 rounded flex items-center justify-center">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <span>© 2026 Smart Exam | All rights reserved.</span>
            </div>
            <div class="flex gap-4">
                <a href="#" class="hover:text-blue-600">Cara Kerja</a>
                <a href="#" class="hover:text-blue-600">Pusat Bantuan</a>
                <a href="#" class="hover:text-blue-600">Hubungi Kami</a>
            </div>
        </footer>
    </div>
</div>
@stack('scripts')
</body>
</html>
