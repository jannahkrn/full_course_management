<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Smart Exam</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2.5 mb-8">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none">
                    <path d="M12 3L4 7V13C4 17.418 7.582 21 12 21C16.418 21 20 17.418 20 13V7L12 3Z" fill="white" fill-opacity="0.3"/>
                    <path d="M9 12L11 14L15 10" stroke="white" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">Smart<span class="text-blue-600">Exam</span></span>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h1 class="text-xl font-bold text-gray-900 mb-1">Selamat Datang</h1>
            <p class="text-sm text-gray-500 mb-6">Masuk ke akun Smart Exam Anda</p>

            @if($errors->any())
            <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-xl">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-600">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            @if(session('success'))
            <div class="mb-5 p-3.5 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="Masukkan email Anda"
                           autocomplete="email"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                  @error('email') border-red-400 @enderror">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           placeholder="Masukkan password Anda"
                           autocomplete="current-password"
                           class="w-full px-3.5 py-2.5 border border-gray-300 rounded-xl text-sm
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold
                               rounded-xl transition-colors mt-2">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-6">© 2026 Smart Exam | All rights reserved.</p>
    </div>

</body>
</html>
