<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Sistem Arsip PTPN4</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📦</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
</head>
<body>

{{-- ================ SIDEBAR ================ --}}
<aside class="sidebar" id="sidebar">
    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
            </svg>
        </div>
        <div class="sidebar-logo-text">
            <div class="sidebar-logo-title">Repository Arsip</div>
            <div class="sidebar-logo-sub">PTPN4 &mdash; Repository</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav style="padding: 0.75rem 0; flex: 1;">

        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>

        @if(in_array(auth()->user()->role, ['Super Admin', 'Administrator', 'User Divisi']))
        <div class="sidebar-section-label">Transaksi Box</div>

        <a href="{{ route('pengajuan.index') }}" class="sidebar-link {{ request()->routeIs('pengajuan.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0121 9.414V19a2 2 0 01-2 2z"/></svg>
            Pengajuan Box
        </a>

        <a href="{{ route('pengisian.index') }}" class="sidebar-link {{ request()->routeIs('pengisian.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Pengisian Data Box
        </a>

        <a href="{{ route('peminjaman.index') }}" class="sidebar-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Peminjaman Box
        </a>
        @endif

        @if(in_array(auth()->user()->role, ['Super Admin', 'Administrator']))
        <a href="{{ route('pengiriman.index') }}" class="sidebar-link {{ request()->routeIs('pengiriman.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zm10 0a2 2 0 11-4 0 2 2 0 014 0zM13 17V7H1v10m12 0V9l4 4m0-4v4m-4-4h-1"/></svg>
            Pengiriman Box
        </a>
        @endif

        <div class="sidebar-section-label">Laporan</div>

        <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Laporan & Export
        </a>

        @if(in_array(auth()->user()->role, ['Super Admin', 'Administrator']))
        <div class="sidebar-section-label">Pengaturan</div>

        <a href="{{ route('divisi.index') }}" class="sidebar-link {{ request()->routeIs('divisi.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Manajemen Divisi
        </a>

        @if(auth()->user()->role === 'Super Admin')
        <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Manajemen User
        </a>
        @endif
        @endif

        <div class="sidebar-section-label">Lainnya</div>

        <a href="{{ route('profile.password') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Ubah Password
        </a>

        <a href="{{ route('tentang') }}" class="sidebar-link {{ request()->routeIs('tentang') ? 'active' : '' }}">
            <svg class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            Tentang Aplikasi
        </a>
    </nav>

    {{-- User Info --}}
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
        </div>
        <div style="overflow: hidden;">
            <div style="color: #e2e8f0; font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ auth()->user()->nama }}
            </div>
            <div style="color: #64748b; font-size: 0.7rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ auth()->user()->role }}
            </div>
        </div>
    </div>
</aside>

{{-- ================ MAIN CONTENT ================ --}}
<div class="main-content">

    {{-- Topbar --}}
    <header class="topbar">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <button onclick="toggleSidebar()" style="display: none; padding: 0.5rem; border-radius: 8px; background: #f1f5f9; border: none; cursor: pointer;" id="sidebarToggle">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;">
                    <path d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                @hasSection('breadcrumb')
                    <div class="breadcrumb">@yield('breadcrumb')</div>
                @endif
                <h1 style="font-size: 1.05rem; font-weight: 700; color: #0f172a;">@yield('page-title', 'Dashboard')</h1>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <div style="text-align: right; margin-right: 0.5rem; display: none;" class="sm-show">
                <div style="font-size: 0.8rem; font-weight: 600; color: #0f172a;">{{ auth()->user()->nama }}</div>
                <div style="font-size: 0.7rem; color: #64748b;">{{ auth()->user()->role }}</div>
            </div>
            <button onclick="document.getElementById('modalLogout').classList.add('active')"
                style="display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background: #fee2e2; color: #b91c1c; border: none; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>
    </header>

    {{-- Flash Messages --}}
    <div style="padding: 0 1.75rem; padding-top: 1.25rem;">
        @if(session('success'))
        <div class="alert alert-success" id="flashAlert">
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error" id="flashAlert">
            <svg style="width:18px;height:18px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
            {{ session('error') }}
        </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="page-content animate-fade-in-up">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer style="padding: 1rem 1.75rem; border-top: 1px solid #f1f5f9; text-align: center; font-size: 0.75rem; color: #94a3b8;">
        &copy; {{ date('Y') }} PT Perkebunan Nusantara IV &mdash; Sistem Arsip Digital. All rights reserved.
    </footer>
</div>

{{-- ================ MODAL LOGOUT ================ --}}
<div class="modal-overlay" id="modalLogout">
    <div class="modal-box" style="max-width: 400px;">
        <div class="modal-header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 40px; height: 40px; background: #fee2e2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <svg style="width:20px;height:20px;color:#dc2626;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <div>
                    <div style="font-weight: 700; color: #0f172a;">Konfirmasi Logout</div>
                    <div style="font-size: 0.8rem; color: #64748b;">Anda akan keluar dari sistem</div>
                </div>
            </div>
            <button onclick="document.getElementById('modalLogout').classList.remove('active')" style="border: none; background: none; cursor: pointer; color: #94a3b8; font-size: 1.25rem;">&times;</button>
        </div>
        <div class="modal-body" style="text-align: center; padding: 1.5rem;">
            <p style="color: #64748b; font-size: 0.9rem;">Apakah Anda yakin ingin keluar dari Sistem Arsip PTPN4?</p>
        </div>
        <div class="modal-footer">
            <button onclick="document.getElementById('modalLogout').classList.remove('active')" class="btn btn-outline btn-sm">Batal</button>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">
                    <svg style="width:15px;height:15px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 16l4-4m0 0l-4-4m4 4H7"/></svg>
                    Ya, Logout
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Overlay Sidebar Mobile --}}
<div onclick="toggleSidebar()" id="sidebarOverlay" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 45;"></div>

@stack('modals')

@stack('scripts')

<script>
    // Auto-hide flash messages after 4 seconds
    setTimeout(() => {
        const f = document.getElementById('flashAlert');
        if (f) { f.style.transition = 'all 0.4s'; f.style.opacity = '0'; f.style.height = '0'; f.style.padding = '0'; setTimeout(() => f.remove(), 400); }
    }, 4000);

    // Mobile sidebar toggle
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
    }

    // Close modal when clicking overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('active');
        });
    });
</script>
</body>
</html>
