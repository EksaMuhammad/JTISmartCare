<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - JTI SmartCare</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Sora:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #4f46e5;
            --secondary: #06b6d4;
            --bg: #f8fafc;
            --sidebar-bg: #355872;
            --sidebar-hover: #355872;
            --sidebar-width: 280px;
            --card-radius: 16px;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-display: 'Sora', sans-serif;
        }

        * { box-sizing: border-box; }
        
        body {
            font-family: var(--font-main);
            background: var(--bg);
            color: #1e293b;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
        }

       .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0px;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
        }
        .sidebar-brand img {
            width: 100px;
            height: 100px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .brand-text {
            font-size: 18px;
            font-weight: 700;
            white-space: nowrap;
            line-height: 1;
            margin-left: -20px;
        }
        .brand-jti   { color: #4dd0e1; }
        .brand-smart { color: #4dd0e1; }
        .brand-care  { color: #9CD5FF; }

        .sidebar-nav {
            padding: 10px 16px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 12px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 8px;
        }

        .nav-item a:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .nav-item a.active {
            background: rgba(255,255,255,0.15);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .nav-item a i { font-size: 1.1rem; width: 24px; text-align: center; }

        .sidebar-footer {
            padding: 20px;
        }

        .user-info {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            background: rgba(0,0,0,0.15);
            border-radius: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 40px; height: 40px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }
        
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .user-info .user-name {
            font-size: 0.85rem; font-weight: 700; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            line-height: 1.2;
        }

        .user-info .user-role {
            font-size: 0.75rem; color: rgba(255,255,255,0.7);
        }

        .btn-logout {
            display: flex; align-items: center; gap: 10px; justify-content: center;
            width: 100%;
            padding: 12px;
            background: rgba(0,0,0,0.15);
            border: none;
            color: rgba(255,255,255,0.9);
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: rgba(0, 0, 0, 0.25);
            color: #fff;
        }

        /* ===== MAIN CONTENT ===== */
       .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 30px 20px; /* kiri lebih kecil */
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            background: #fff;
        }

        .card-header-custom {
            padding: 20px 24px 0;
            background: transparent;
            border-bottom: none;
            font-weight: 700;
            font-size: 1.1rem;
            color: #1e293b;
            font-family: var(--font-display);
        }

        .card-body {
            padding: 24px;
        }

        /* ===== MOBILE TOGGLE ===== */
        .sidebar-toggle {
            display: none;
            background: #fff; border: 1px solid #e2e8f0;
            font-size: 1.3rem; color: #475569;
            border-radius: 8px; padding: 6px 12px;
            margin-bottom: 20px;
        }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 20px; }
            .sidebar-toggle { display: inline-block; }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .fade-in { animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">
     <div class="sidebar-brand">
        <img src="{{ asset('assets/images/logo.png') }}" alt="JTI SmartCare">
        <div class="brand-text">
            <span class="brand-jti">JTI </span><span class="brand-smart">Smart</span><span class="brand-care">Care</span>
        </div>
    </div>

    <div class="sidebar-nav">
        @auth
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('diagnosis.form') }}" class="{{ request()->routeIs('diagnosis.form') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse"></i> Isi Kuesioner
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Diagnosis
                </a>
            </div>
        @endauth
    </div>

    @auth
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8fafc&color=355872&bold=true" alt="Avatar">
            </div>
            <div style="overflow:hidden">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-right"></i> Keluar
            </button>
        </form>
    </div>
    @endauth
</nav>

    {{-- MAIN CONTENT --}}
    <div class="main-content">
        <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i> Menu</button>
        
        <div class="fade-in">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius:12px;border:none;background:#dcfce7;color:#16a34a;">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('open');
        });
    </script>
    @yield('scripts')
    </body>
</html>
