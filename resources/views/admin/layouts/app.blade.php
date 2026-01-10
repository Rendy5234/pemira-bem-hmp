<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Pemira BEM & HMP</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        /* Navbar */
        .navbar {
            background-color: #2563eb;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.875rem;
        }
        .btn-logout {
            background-color: #dc2626;
            color: white;
        }
        .btn-logout:hover {
            background-color: #b91c1c;
        }
        /* Layout */
        .container {
            display: flex;
            min-height: calc(100vh - 64px);
        }
        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: white;
            padding: 1rem;
            border-right: 1px solid #e5e7eb;
        }
        .sidebar a {
            display: block;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            color: #374151;
            text-decoration: none;
            border-radius: 4px;
        }
        .sidebar a:hover {
            background-color: #f3f4f6;
        }
        .sidebar a.active {
            background-color: #dbeafe;
            color: #2563eb;
            font-weight: bold;
        }
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem;
        }
        /* Alert */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            border: 1px solid;
        }
        .alert-success {
            background-color: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }
        .alert-error {
            background-color: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }
    </style>
</head>
<body>
    
    @auth('admin')
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">Pemira Admin Panel</div>
        <div class="navbar-user">
            <span>{{ Auth::guard('admin')->user()->name_admin }} ({{ Auth::guard('admin')->user()->role }})</span>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-logout">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="{{ route('admin.dashboard') }}" class="active">Dashboard</a>
            <a href="#">Event</a>
            <a href="#">Kandidat</a>
            <a href="#">Laporan</a>
            <a href="#">Mahasiswa</a>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    @else
    <!-- Content for guest -->
    @yield('content')
    @endauth

</body>
</html>