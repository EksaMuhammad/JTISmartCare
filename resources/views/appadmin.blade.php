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
            --sidebar-hover: rgba(255,255,255,0.10);

            --sidebar-width: 255px;

            --card-radius: 16px;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-display: 'Sora', sans-serif;
        }

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
            left: 0;
            top: 0;
            z-index: 1000;

            display: flex;
            flex-direction: column;

            transition: transform 0.3s ease;

            padding: 0;
        }

        /* ===== BRAND ===== */

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;

            padding: 10px 14px 12px;

            border-bottom: 1px solid rgba(255,255,255,0.18);
        }

        .sidebar-brand img {
            width: 72px;
            height: 72px;
            object-fit: contain;

            margin-right: -8px;
        }

        .brand-text {
            font-size: 20px;
            font-weight: 800;
            line-height: 1;
            margin-top: 2px;
        }

        .brand-jti,
        .brand-smart {
            color: #38d6e8;
        }

        .brand-care {
            color: #9bd2ff;
        }

        /* ===== MENU ===== */

        .sidebar-nav {
            padding: 14px 10px;
            flex: 1;
            overflow-y: auto;
        }

        .nav-item a {
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 11px 14px;

            border-radius: 12px;

            color: rgba(255,255,255,0.92);
            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            transition: all 0.2s ease;

            margin-bottom: 6px;
        }

        .nav-item a i {
            font-size: 16px;
            width: 18px;
            text-align: center;
        }

        /* hover */
        .nav-item a:hover {
            background: rgba(255,255,255,0.08);
            color: #fff;
        }

        /* active menu */
        .nav-item a.active {
            background: rgba(255,255,255,0.22);

            color: #fff;

            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.12),
                0 3px 10px rgba(0,0,0,0.18);
        }

        /* ===== FOOTER ===== */

        .sidebar-footer {
            padding: 12px 10px 14px;

            border-top: 1px solid rgba(255,255,255,0.12);
        }

        /* user info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;

            padding: 8px 10px;

            background: rgba(255,255,255,0.18);

            border-radius: 8px;

            margin-bottom: 10px;
        }

        .user-avatar {
            width: 34px;
            height: 34px;

            border-radius: 50%;

            overflow: hidden;

            flex-shrink: 0;

            background: #fff;
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-name {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
        }

        .user-role {
            font-size: 11px;
            color: rgba(255,255,255,0.75);
        }

        /* logout */
        .btn-logout {
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 10px;

            padding: 10px 12px;

            border: none;
            border-radius: 8px;

            background: rgba(255,255,255,0.15);

            color: #fff;

            font-size: 14px;
            font-weight: 500;

            transition: 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.25);
        }

        .btn-logout i {
            font-size: 18px;
        }

        /* ===== MAIN CONTENT ===== */

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;

            padding: 24px;
        }

        /* ===== MOBILE ===== */

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: inline-block;

                background: #fff;
                border: 1px solid #e2e8f0;

                padding: 6px 12px;

                border-radius: 8px;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

{{-- SIDEBAR --}}
<nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
        <img src="{{ asset('assets/images/Logo.png') }}" alt="JTI SmartCare">

        <div class="brand-text">
            <span class="brand-jti">JTI</span>
            <span class="brand-smart">Smart</span><span class="brand-care">Care</span>
        </div>
    </div>

    <div class="sidebar-nav">

    {{-- DASHBOARD --}}
    <div class="nav-item">
        <a href="{{ route('admin.dashboard') }}"
           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">

            <i class="bi bi-house-door"></i>
            Dashboard Admin
        </a>
    </div>

    {{-- MONITORING --}}
    <div class="nav-item">
        <a href="{{ route('admin.monitoring') }}"
        class="{{ request()->routeIs('admin.monitoring') ? 'active' : '' }}">

            <i class="bi bi-graph-up-arrow"></i>
            Monitoring & Laporan
        </a>
    </div>

    {{-- KNOWLEDGE BASE --}}
    <div class="nav-item">
        <a href="{{ route('admin.knowledge.index') }}"
           class="{{ request()->routeIs('admin.knowledge.index') ? 'active' : '' }}">

            <i class="bi bi-database"></i>
            Knowledge Base
        </a>
    </div>

    {{-- ARTIKEL --}}
    <div class="nav-item">
        <a href="{{ route('admin.articles') }}"
            class="{{ request()->routeIs('admin.articles') ? 'active' : '' }}">

            <i class="bi bi-file-earmark-text"></i>
            Kelola Artikel
        </a>
    </div>

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
