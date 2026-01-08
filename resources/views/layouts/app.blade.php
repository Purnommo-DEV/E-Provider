<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin MyRepublic') — Dashboard Admin</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <!-- Font Poppins (modern & clean) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind + DaisyUI + AlpineJS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.10/dist/full.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.tailwindcss.min.css">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .swal2-popup { font-family: 'Poppins', sans-serif !important; }
        /* Compact table & UI (mirip contoh Anda) */
        .table.table-zebra { font-size: 0.875rem; }
        .table.table-zebra th, .table.table-zebra td { padding: 0.45rem 0.6rem; vertical-align: middle; }
        .table.table-zebra thead th { font-size: 0.86rem; padding: 0.5rem 0.65rem; }
        .btn { padding: 0.38rem 0.7rem; font-size: 0.88rem; }
        .btn.btn-lg { padding: 0.45rem 0.9rem; font-size: 0.92rem; }
        .btn.btn-sm { padding: 0.28rem 0.5rem; font-size: 0.78rem; }
        .input, .select, .textarea { padding: 0.42rem 0.6rem; font-size: 0.9rem; }
        .modal-box { padding: 0.9rem !important; }
        .modal-box .text-3xl { font-size: 1.15rem !important; }
        @media (max-width: 768px) {
            .table.table-zebra { font-size: 0.82rem; }
            .btn { padding: 0.28rem 0.5rem; font-size: 0.82rem; }
        }
    </style>
</head>
<body class="h-full bg-gray-100" x-data="{ sidebarOpen: false }">
<div class="flex h-screen overflow-hidden">
    {{-- SIDEBAR — Hanya untuk user terautentikasi (Breeze sudah handle) --}}
    @auth
    {{-- SIDEBAR MyRepublic Theme --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 lg:w-72
                  bg-gradient-to-b from-purple-900 to-purple-950
                  text-purple-100
                  transform transition-all duration-300
                  lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
        {{-- HEADER LOGO --}}
        <div class="flex items-center justify-between p-6 border-b border-purple-800">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center
                            text-xl font-extrabold text-purple-950 shadow-lg">
                    MR
                </div>
                <div>
                    <h1 class="text-lg font-bold text-purple-50">MyRepublic</h1>
                    <p class="text-xs opacity-80 text-purple-200">Admin Dashboard</p>
                </div>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden text-purple-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- MENU ADMIN --}}
        <nav class="mt-10 px-6">
            <ul class="space-y-3">
                {{-- DASHBOARD --}}
                <li>
                    <a href="#"
                       class="flex items-center space-x-4 px-5 py-3 rounded-xl transition-all
                              {{ request()->is('admin/dashboard') ? 'bg-orange-500 text-purple-950 font-semibold shadow-md' : 'hover:bg-purple-800 hover:translate-x-1 hover:shadow' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3"/>
                        </svg>
                        <span class="text-sm">Dashboard</span>
                    </a>
                </li>

                {{-- BANNER & PROMO — SATU-SATUNYA YANG SUDAH ADA ROUTE NYATA --}}
                <li>
                    <a href="{{ route('admin.banners.index') }}"
                       class="flex items-center space-x-4 px-5 py-3 rounded-xl transition-all
                              {{ request()->routeIs('admin.banners.*') ? 'bg-orange-500 text-purple-950 font-semibold shadow-md' : 'hover:bg-purple-800 hover:translate-x-1 hover:shadow' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4 4 4 0 004-4V5z"/>
                        </svg>
                        <span class="text-sm">Banner & Promo</span>
                    </a>
                </li>

                {{-- KEUNGGULAN --}}
                <li>
                    <a href="{{ route('admin.features.index') }}"
                       class="flex items-center space-x-4 px-5 py-3 rounded-xl transition-all
                              {{ request()->routeIs('admin.features.*') ? 'bg-orange-500 text-purple-950 font-semibold shadow-md' : 
                              'hover:bg-purple-800 hover:translate-x-1 hover:shadow' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">Keunggulan</span>
                    </a>
                </li>

            {{-- PAKET INTERNET — Dropdown Besar --}}
                <li x-data="{ open: {{ request()->routeIs('admin.categories.*') || request()->routeIs('admin.payment-promos.*') || request()->routeIs('admin.package-types.*') || request()->routeIs('admin.packages.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex  px-5 py-3 rounded-xl hover:bg-purple-800 transition-all">
                        <div class="flex space-x-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 0H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="text-sm font-medium">Manajemen Paket Internet</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" class="mt-2 ml-8 space-y-2 border-l-2 border-purple-600 pl-4">
                        <li>
                            <a href="{{ route('admin.categories.index') }}"
                               class="{{ request()->routeIs('admin.categories.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Kategori Utama
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.payment-promos.index') }}"
                               class="{{ request()->routeIs('admin.payment-promos.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Promo Pembayaran
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.package-types.index') }}"
                               class="{{ request()->routeIs('admin.package-types.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Tipe Paket
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.streaming-addons.index') }}"
                               class="{{ request()->routeIs('admin.streaming-addons.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Streaming Addons
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.benefits.index') }}"
                               class="{{ request()->routeIs('admin.benefits.*')
                                    ? 'text-orange-400 font-semibold'
                                    : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Benefit / OTT
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.packages.index') }}"
                               class="{{ request()->routeIs('admin.packages.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Data Paket Internet
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- HIBURAN TERLENGKAP — Dropdown --}}
                <li x-data="{ open: {{ request()->routeIs('admin.tv-addon.*') || request()->routeIs('admin.channels.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-5 py-3 rounded-xl hover:bg-purple-800 transition-all">
                        <div class="flex items-center space-x-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1.5 3h9l-1.5-3L15 17M20 5H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V7a2 2 0 00-2-2z"/>
                            </svg>
                            <span class="text-sm font-medium">Hiburan Terlengkap (TV Add-on)</span>
                        </div>
                        <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <ul x-show="open" class="mt-2 ml-8 space-y-2 border-l-2 border-purple-600 pl-4">
                        <li>
                            <a href="{{ route('admin.channels.index') }}"
                               class="{{ request()->routeIs('admin.channels.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Kelola Channel & Platform
                            </a>
                        </li>                        
                        <li>
                            <a href="{{ route('admin.tv-addon.index') }}"
                               class="{{ request()->routeIs('admin.tv-addon.*') ? 'text-orange-400 font-semibold' : 'text-purple-200 hover:text-orange-300' }} text-sm block py-1">
                                Pengaturan TV Add-on (Harga & Gambar)
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- SYARAT & KETENTUAN --}}
                <li>
                    <a href="{{ route('admin.terms.index') }}"
                       class="{{ request()->routeIs('admin.terms.*') ? 'bg-orange-500 text-purple-950 font-semibold shadow-md' : 'hover:bg-purple-800 hover:translate-x-1 hover:shadow' }} flex items-center space-x-4 px-5 py-3 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="text-sm">Syarat & Ketentuan</span>
                    </a>
                </li>

                {{-- DATA REGISTRASI --}}
                <li>
                    <a href="#"
                       class="flex items-center space-x-4 px-5 py-3 rounded-xl transition-all
                              hover:bg-purple-800 hover:translate-x-1 hover:shadow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-12 0v1z"/>
                        </svg>
                        <span class="text-sm">Data Registrasi</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- LOGOUT --}}
        <div class="absolute bottom-0 w-full p-6 border-t border-purple-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center space-x-3 px-5 py-3
                               bg-red-600 hover:bg-red-700 rounded-xl transition-all font-medium
                               text-white shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                    </svg>
                    <span class="text-sm">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    @endauth

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">
        @auth
        <div class="lg:hidden bg-purple-900 p-4">
            <button @click="sidebarOpen = true" class="text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        @endauth
        <header class="bg-white shadow-sm border-b">
            <div class="w-full flex justify-between items-center px-4 sm:px-6 lg:px-8 py-5">
                <h1 class="text-2xl font-bold text-purple-900">@yield('title', 'Admin MyRepublic')</h1>
                @auth
                <div class="text-gray-700">
                    Halo, <span class="font-bold text-orange-600">{{ auth()->user()->name }}</span>
                </div>
                @endauth
            </div>
        </header>
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @include('sweetalert::alert')
                @yield('content')
            </div>
        </main>
        <!-- Footer Simple -->
        <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
            <div class="max-w-7xl mx-auto px-6 text-center text-sm text-gray-600">
                © {{ date('Y') }} MyRepublic Indonesia — Admin Panel<br>
                Dibuat dengan ❤️ untuk Management Konten Landing Page
            </div>
        </footer>
    </div>
</div>

<!-- Scripts Global -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
</script>
@stack('scripts')
</body>
</html>