<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Exam - Management</title>
</head>
<body>
    <div style="display: flex;">
        <!-- Sidebar -->
        <aside style="width: 250px; border-right: 1px solid #ccc; height: 100vh;">
            <h2>SMART EXAM</h2>
            <nav>
                <ul>
                    <li><strong>Menu</strong></li>
                    <li>Mata Kuliah Saya</li>
                    <li>Aktivitas Pembelajaran</li>
                    <hr>
                    <li><strong>Administrasi Platform</strong></li>
                    <li>Manajemen Pengguna</li>
                    <li>
                        <a href="{{ route('admin.courses.index') }}">Manajemen Mata Kuliah</a>
                    </li>
                </ul>
            </nav>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit">Keluar</button>
            </form>
        </aside>

        <!-- Main Content -->
        <main style="flex-grow: 1; padding: 20px;">
            <header style="display: flex; justify-content: space-between;">
                <div>@yield('breadcrumb')</div>
                <div>
                    <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role }})
                </div>
            </header>

            <section>
                @yield('content')
            </section>
        </main>
    </div>
</body>
</html>