<!DOCTYPE html>
<html lang="id" data-theme="light">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MyRepublic - Internet Cepat Unlimited untuk Rumah & Bisnis</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Internet super lancar tanpa kuota, upload download simetris, low latency. Mulai Rp235.000/bulan + hiburan lengkap.">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <style>
            :root {
                --primary: #7c3aed;
                --primary-dark: #5b21b6;
                --primary-light: #a78bfa;
                --text: #1f2937;
                --text-light: #4b5563;
                --bg-light: #f9fafb;
                --card-gap: 1rem; /* Gap default untuk mobile */
                --card-padding-x: 1rem; /* Padding horizontal default untuk mobile */
            }
            body {
                font-family: 'Inter', system-ui, -apple-system, sans-serif;
                color: var(--text);
                background: var(--bg-light);
                scroll-behavior: smooth;
                font-size: 1rem;
                line-height: 1.6;
            }
            .container {
                max-width: 1280px;
            }
            h1, h2, h3 {
                font-weight: 700;
                line-height: 1.2;
            }
            .section-title {
                font-size: 2.25rem;
                @media (min-width: 768px) { font-size: 2.75rem; }
            }
            .hero-section {
                background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 50%, #a855f7 100%);
                color: white;
                padding: 8rem 0 10rem;
                clip-path: polygon(0 0, 100% 0, 100% 90%, 0 100%);
                position: relative;
                overflow: hidden;
            }
            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(circle at 30% 70%, rgba(255,255,255,0.15) 0%, transparent 60%);
            }
            .steps-container {
                display: flex;
                justify-content: center;
                gap: 2rem;
                flex-wrap: wrap;
            }
            .step-card {
                flex: 1 1 280px;
                max-width: 320px;
                text-align: center;
                padding: 2rem 1.5rem;
                border-radius: 1.25rem;
                background: white;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
                transition: all 0.3s ease;
                position: relative;
            }
            .step-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(124,58,237,0.15);
            }
            .step-number {
                position: absolute;
                top: -1.25rem;
                left: 50%;
                transform: translateX(-50%);
                width: 50px;
                height: 50px;
                background: var(--primary);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                font-weight: bold;
                box-shadow: 0 4px 10px rgba(124,58,237,0.3);
            }
            .step-icon {
                font-size: 3rem;
                color: var(--primary);
                margin-bottom: 1rem;
            }
            .card {
                border-radius: 1.25rem;
                overflow: hidden;
                transition: all 0.3s ease;
                background: white;
                box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            }
            .card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 40px rgba(124,58,237,0.18);
            }
            #categoryInfo {
                opacity: 0;
                transition: opacity 0.5s ease;
            }
            #categoryInfo.visible {
                opacity: 1;
            }
            .tabs .tab {
                font-size: 1.1rem; /* Lebih besar dari tab lain */
                padding: 0.8rem 1.8rem; /* Padding lebih besar */
                color: white; /* Warna teks putih untuk kontras */
                border-radius: 9999px;
                transition: all 0.25s;
            }
            .tabs .tab.active {
                background: var(--primary) !important;
                color: white !important;
                font-weight: 600;
            }
            .tab-container {
                position: relative; /* Agar arrows bisa absolute positioned */
                overflow-x: auto;
                scrollbar-width: none;
            }
            .tab-container::-webkit-scrollbar { display: none; }
            .tab-container::before,
            .tab-container::after {
                content: '';
                position: absolute;
                top: 0;
                bottom: 0;
                width: 2.5rem;
                z-index: 10;
                pointer-events: none;
                transition: opacity 0.3s ease;
                opacity: 0; /* Default hidden, muncul via media atau JS */
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: var(--primary);
            }
            .tab-container::before {
                left: 0;
                background: linear-gradient(to right, var(--bg-light) 0%, transparent 100%); /* Gradient fade kiri */
                content: '❮'; /* Arrow kiri */
            }
            .tab-container::after {
                right: 0;
                background: linear-gradient(to left, var(--bg-light) 0%, transparent 100%); /* Gradient fade kanan */
                content: '❯'; /* Arrow kanan */
            }
            .tab-container.at-start::before {
                opacity: 0;
            }
            .tab-container.at-end::after {
                opacity: 0;
            }
            .tabs.tabs-boxed {
                background: linear-gradient(to right, #a78bfa, #7c3aed); /* Gradient ungu untuk beda */
                padding: 0.5rem;
                box-shadow: 0 4px 15px rgba(124,58,237,0.25); /* Shadow lebih tebal */
            }
            .feature-icon {
                width: 64px;
                height: 64px;
                background: rgba(124,58,237,0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                color: var(--primary);
                margin: 0 auto 1.25rem;
            }
            .channel-marquee {
                overflow: hidden;
                white-space: nowrap;
                position: relative;
            }
            .channel-track {
                display: inline-flex;
                animation: marquee 40s linear infinite;
            }
            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }
            .channel-item {
                flex: 0 0 auto;
                margin: 0 1.5rem;
                opacity: 0.92;
                transition: opacity 0.4s;
            }
            .channel-item:hover { opacity: 1; }
            footer {
                background: #1e1b4b;
            }
            @media (max-width: 640px) {
                :root {
                    --card-padding-x: 0.5rem; /* Kurangi dari 1rem ke 0.5rem agar card lebih lebar */
                    --card-gap: 0.75rem; /* Kurangi gap antar card jika multi */
                }
                .package-card {
                    flex: 0 0 calc(100% - 2 * var(--card-padding-x)); /* Sekarang minus 1rem total, lebih full */
                    height: 580px; /* Optional: Besarkan height sedikit untuk konsistensi jika konten panjang */
                }
                #packageCarousel {
                    padding: 0 var(--card-padding-x); /* Sesuaikan padding carousel */
                }
                .section-title { font-size: 1.875rem; }
                .hero-section { padding: 6rem 0 8rem; }
                .tabs .tab {
                    font-size: 0.85rem; /* Terkecil */
                    padding: 0.5rem 1rem;
                    white-space: nowrap; /* Cegah wrap teks agar tidak bertabrakan vertikal */
                }
                .tab-container {
                    display: flex; /* Pastikan flex untuk scroll horizontal */
                    justify-content: flex-start; /* Align kiri agar scroll natural */
                }
            }
            @media (max-width: 768px) {
                .steps-container {
                    flex-direction: column;
                    align-items: center;
                }
                .step-card {
                    max-width: 360px;
                }
                .tabs .tab {
                    font-size: 0.9rem; /* Lebih kecil lagi */
                    padding: 0.6rem 1.2rem;
                }
                .tabs.tabs-boxed {
                    padding: 0.4rem; /* Kurangi padding container */
                }
                .tab-container {
                    padding-left: 1rem;
                    padding-right: 1rem;
                }
                .tab-container:not(.at-start)::before,
                .tab-container:not(.at-end)::after {
                    opacity: 1; /* Tampil jika ada scroll potensial */
                }
            }
            @media (max-width: 1024px) { /* Tablet ke bawah */
                .tabs .tab {
                    font-size: 1rem; /* Kecilkan font sedikit */
                    padding: 0.7rem 1.5rem; /* Padding lebih kecil */
                }
            }
            @keyframes channel-loop {
              0% {
                transform: translateX(0);
              }
              100% {
                transform: translateX(-50%);
              }
            }
            .animate-channel-loop {
              animation: channel-loop 125s linear infinite;
            }
            /* pause saat hover (UX) */
            .animate-channel-loop:hover {
              animation-play-state: paused;
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 1s ease-out forwards;
            }
            .scrollbar-hide::-webkit-scrollbar { display: none; }
            .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
            /* Package Card - RESPONSIVE CAROUSEL FIX */
            .package-card {
                flex: 0 0 calc(100% - 2 * var(--card-padding-x)); /* Mobile: ~1 card full (minus padding) */
                scroll-snap-align: start;
                box-sizing: border-box;
                height: 560px; /* Fixed height untuk konsistensi */
                min-height: auto;
                border-bottom: 8px solid rgba(124, 58, 237, 0.1); /* Batas tipis ungu muda */
            }
            .package-card s {
                text-decoration: line-through;
                text-decoration-thickness: 2px;   /* tebal coretan */
                text-decoration-color: #9ca3af;   /* warna abu-abu */
                opacity: 0.75;
            }

            .package-card .font-extrabold {
                font-weight: 900;
            }
            /* Tablet / Small Desktop: 2 cards visible */
            @media (min-width: 640px) {
                .package-card {
                    flex: 0 0 calc((100% - 2 * var(--card-padding-x) - 1 * var(--card-gap)) / 2);
                }
            }
            /* Medium Desktop: 3 cards visible */
            @media (min-width: 768px) {
                :root {
                    --card-gap: 1.5rem;
                    --card-padding-x: 2rem;
                }
                .package-card {
                    flex: 0 0 calc((100% - 2 * var(--card-padding-x) - 2 * var(--card-gap)) / 3);
                }
            }
            /* Large Desktop: 4 cards visible */
            @media (min-width: 1024px) {
                .package-card {
                    flex: 0 0 calc((100% - 2 * var(--card-padding-x) - 3 * var(--card-gap)) / 4);
                }
            }
            /* Extra Large: tetap 4 cards */
            @media (min-width: 1280px) {
                .package-card {
                    flex: 0 0 calc((100% - 2 * var(--card-padding-x) - 3 * var(--card-gap)) / 4);
                }
            }
            /* Scroll internal untuk fitur/benefit jika panjang */
            .features-scroll {
                max-height: 160px;
                overflow-y: auto;
                padding-right: 8px;
                scrollbar-width: thin;
                position: relative; /* Tambah untuk ::after */
            }
            .features-scroll::-webkit-scrollbar {
                width: 6px;
            }
            .features-scroll::-webkit-scrollbar-thumb {
                background-color: rgba(139, 92, 246, 0.4);
                border-radius: 3px;
            }
            .features-scroll::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 1rem;
                background: linear-gradient(to bottom, transparent, rgba(255, 255, 255, 0.8)); /* Tambah fade putih halus sebagai batas */
                pointer-events: none;
            }
            /* Style tombol disable/hide */
            #prevPkgBtn.disabled, #nextPkgBtn.disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            #prevPkgBtn.hidden, #nextPkgBtn.hidden {
                display: none;
            }
            #packageCarousel {
                padding: 0 var(--card-padding-x);
            }
            #packageTrack {
                scroll-padding: var(--card-padding-x);
            }
            .package-header {
                background: linear-gradient(to bottom, #00bfff, #1e90ff);
                position: relative;
                padding: 1.5rem 1rem;
                color: white;
                text-align: center;
            }
            .wifi-icon {
                font-size: 2rem;
                margin-bottom: 0.5rem;
            }
            .speed-big {
                font-size: 3rem;
                line-height: 1;
            }
            .accordion-btn {
                background: #e0f7fa;
                color: #00838f;
                font-weight: bold;
            }
            .benefit-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.75rem;
                border-bottom: 1px solid #eee;
            }
            .benefit-icon {
                width: 32px;
                height: 32px;
            }
            .unlimited-tag {
                background: #f0f4f8;
                color: #333;
                padding: 1rem;
                text-align: center;
                font-weight: bold;
                border-top: 1px solid #ddd;
            }
        </style>

    </head>
    <body class="overflow-x-hidden">

        <!-- 1. HERO BANNER CAROUSEL (FINAL FIX) -->
        <section class="relative py-10 md:py-14 bg-gradient-to-b from-slate-50 to-white">
            <div class="max-w-7xl mx-auto px-6">
                <!-- SCROLL CONTAINER (INI YANG DI-SCROLL) -->
                <div id="bannerCarousel"
                     class="relative w-full aspect-[1920/500]
                            overflow-hidden rounded-3xl
                            shadow-[0_40px_120px_-50px_rgba(0,0,0,0.45)]
                            bg-gray-200">

                    <!-- TRACK -->
                    <div id="bannerTrack"
                         class="flex h-full w-full">
                        @foreach ($banners as $banner)
                            <div class="banner-item w-full h-full flex-shrink-0">
                                <img
                                    src="{{ asset('storage/' . $banner->image) }}"
                                    alt="Banner {{ $loop->iteration }}"
                                    class="w-full h-full object-contain object-center bg-black"
                                    loading="lazy"
                                />

                            </div>
                        @endforeach
                    </div>
                    <!-- NAV BUTTONS -->
                    <div class="absolute inset-y-0 left-0 right-0 flex items-center justify-between px-4 md:px-8 z-10">
                        <button type="button"
                                class="prev-btn w-12 h-12 rounded-full
                                       bg-black/50 backdrop-blur-md
                                       text-white text-xl hover:bg-black/70 transition">
                            ❮
                        </button>
                        <button type="button"
                                class="next-btn w-12 h-12 rounded-full
                                       bg-black/50 backdrop-blur-md
                                       text-white text-xl hover:bg-black/70 transition">
                            ❯
                        </button>
                    </div>
                </div>
                <!-- INDICATOR DOTS -->
                <div class="flex justify-center gap-3 mt-8">
                    @foreach ($banners as $banner)
                        <button
                            type="button"
                            class="indicator w-3 h-3 rounded-full
                                   bg-gray-400/40 transition-all
                                   {{ $loop->first ? 'bg-gray-900 scale-125' : '' }}">
                        </button>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 2. FORM DAFTAR -->
        <section id="alur" class="py-16 bg-gradient-to-b from-purple-50/50 to-white">
            <div class="container mx-auto px-6">
                <h2 class="section-title text-center mb-10 text-purple-900">Cara Berlangganan MyRepublic</h2>
                <p class="text-center text-lg text-gray-600 mb-12 max-w-3xl mx-auto">
                    Hanya 3 langkah mudah untuk menikmati internet unlimited super cepat
                </p>
                <div class="steps-container">
                    <div class="step-card" data-aos="fade-up" data-aos-delay="100">
                        <div class="step-number">1</div>
                        <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                        <h3 class="text-xl font-bold mb-4 text-purple-900">Registrasi</h3>
                        <p class="text-gray-600">Lengkapi dan kirim form berikut ini</p>
                    </div>
                    <div class="step-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="step-number">2</div>
                        <div class="step-icon"><i class="fas fa-headset"></i></div>
                        <h3 class="text-xl font-bold mb-4 text-purple-900">Verifikasi</h3>
                        <p class="text-gray-600">Tim kami akan segera menghubungi Anda</p>
                    </div>
                    <div class="step-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-number">3</div>
                        <div class="step-icon"><i class="fas fa-tools"></i></div>
                        <h3 class="text-xl font-bold mb-4 text-purple-900">Instalasi</h3>
                        <p class="text-gray-600">Lacak proses instalasi kemudian nikmati layanan MyRepublic!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. FORM DAFTAR (Modern Input + Floating Label) -->
        <section id="registrasi" class="py-16 -mt-12">
            <div class="container mx-auto px-6 max-w-xl">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-4 text-purple-900 animate-fade-in-up">
                    Daftar Sekarang
                </h2>
                <p class="text-center text-gray-600 mb-8 animate-fade-in-up delay-150">
                    Dapatkan konsultasi gratis & penawaran terbaik hari ini!
                </p>
                <div class="bg-white rounded-3xl shadow-xl border border-purple-100/50 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-700 to-indigo-700 p-6 text-white text-center">
                        <i class="fas fa-shield-alt text-3xl mb-3"></i>
                        <h3 class="text-xl font-bold">Data Anda Aman & Terlindungi</h3>
                        <p class="text-purple-100 text-sm mt-1">🔒 Enkripsi end-to-end | 📱 Konfirmasi via WhatsApp</p>
                    </div>
                    <form id="registration-form" method="POST" action="{{ route('leads.store') }}" class="p-6 md:p-8 space-y-4">
                        @csrf
                        <input type="hidden" name="g-recaptcha-response" id="recaptcha-token">
                    <!-- Alamat (trigger) -->
                        <div class="relative grid items-center">
                            <input
                                id="address" name="address" type="text"
                                class="peer h-14 w-full rounded-xl border border-gray-300 px-5 text-base 
                                       focus:border-purple-500 focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                placeholder=" "
                            required>
                            <label
                                for="address"
                                class="absolute left-5 text-gray-500 text-base font-medium pointer-events-none 
                                       transition-all duration-200
                                       peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                       peer-[&:not(:placeholder-shown)]:-translate-y-4
                                       peer-[&:not(:placeholder-shown)]:text-xs
                                       peer-[&:not(:placeholder-shown)]:text-purple-600"
                            >
                                Alamat Lengkap
                            </label>
                            <p id="address-error" class="text-red-600 text-sm mt-1 hidden"></p>
                        </div>

                        <!-- Kelompok Alamat Tambahan (awalnya hidden) -->
                        <div id="address-detail-group" class="space-y-5 hidden">

                            <!-- Kelurahan -->
                            <div class="relative grid items-center">
                                <input
                                    id="kelurahan" name="kelurahan" type="text"
                                    class="peer h-14 w-full rounded-xl border border-gray-300 px-5 text-base 
                                           focus:border-purple-500 focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                    placeholder=" "
                                required>
                                <label
                                    for="kelurahan"
                                    class="absolute left-5 text-gray-500 text-base font-medium pointer-events-none 
                                           transition-all duration-200
                                           peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                           peer-[&:not(:placeholder-shown)]:-translate-y-4
                                           peer-[&:not(:placeholder-shown)]:text-xs
                                           peer-[&:not(:placeholder-shown)]:text-purple-600"
                                >
                                    Kelurahan / Desa
                                </label>
                                <p id="kelurahan-error" class="text-red-600 text-sm mt-1 hidden"></p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">

                                <!-- RT -->
                                <div class="relative grid items-center">
                                    <input
                                        id="rt" name="rt" type="text" maxlength="5"
                                        class="peer h-14 w-full rounded-xl border border-gray-300 px-5 text-base 
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                        placeholder=" "
                                    >
                                    <label
                                        for="rt"
                                        class="absolute left-5 text-gray-500 text-base font-medium pointer-events-none 
                                               transition-all duration-200
                                               peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                               peer-[&:not(:placeholder-shown)]:-translate-y-4
                                               peer-[&:not(:placeholder-shown)]:text-xs
                                               peer-[&:not(:placeholder-shown)]:text-purple-600"
                                    required>
                                        RT
                                    </label>
                                    <p id="rt-error" class="text-red-600 text-sm mt-1 hidden"></p>
                                </div>

                                <!-- Blok / RW (bisa diganti sesuai kebutuhan) -->
                                <div class="relative grid items-center">
                                    <input
                                        id="blok" name="blok" type="text" maxlength="10"
                                        class="peer h-14 w-full rounded-xl border border-gray-300 px-5 text-base 
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                        placeholder=" "
                                    >
                                    <label
                                        for="blok"
                                        class="absolute left-5 text-gray-500 text-base font-medium pointer-events-none 
                                               transition-all duration-200
                                               peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                               peer-[&:not(:placeholder-shown)]:-translate-y-4
                                               peer-[&:not(:placeholder-shown)]:text-xs
                                               peer-[&:not(:placeholder-shown)]:text-purple-600"
                                    >
                                        Blok / RW
                                    </label>
                                </div>

                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="relative grid items-center">
                            <input
                                id="name" name="name" type="text"
                                class="peer h-14 w-full rounded-xl border border-gray-300
                                       px-5 text-base focus:border-purple-500
                                       focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                placeholder=" " required
                            >
                            <label
                                for="name"
                                class="absolute left-5 text-gray-500 text-base font-medium
                                       pointer-events-none transition-all duration-200
                                       peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                       peer-[&:not(:placeholder-shown)]:-translate-y-4
                                       peer-[&:not(:placeholder-shown)]:text-xs
                                       peer-[&:not(:placeholder-shown)]:text-purple-600"
                            >
                                Nama Lengkap
                            </label>
                        </div>

                        <!-- Nomor WhatsApp -->
                        <div class="relative grid items-center mt-4">
                            <input
                                id="email" name="email" type="email"
                                class="peer h-14 w-full rounded-xl border border-gray-300
                                       px-5 text-base focus:border-purple-500
                                       focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                placeholder=" " required
                            >
                            <label
                                for="email"
                                class="absolute left-5 text-gray-500 text-base font-medium
                                       pointer-events-none transition-all duration-200
                                       peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                       peer-[&:not(:placeholder-shown)]:-translate-y-4
                                       peer-[&:not(:placeholder-shown)]:text-xs
                                       peer-[&:not(:placeholder-shown)]:text-purple-600"
                            >
                                Email
                            </label>
                        </div>

                        <!-- Email -->
                        <div class="relative grid items-center mt-4">
                            <input
                                id="phone" name="phone" type="tel"
                                class="peer h-14 w-full rounded-xl border border-gray-300
                                       px-5 text-base focus:border-purple-500
                                       focus:ring-2 focus:ring-purple-400/30 focus:outline-none"
                                placeholder=" " required
                                pattern="[0-9]{9,15}"
                            >
                            <label
                                for="phone"
                                class="absolute left-5 text-gray-500 text-base font-medium
                                       pointer-events-none transition-all duration-200
                                       peer-focus:-translate-y-4 peer-focus:text-xs peer-focus:text-purple-600
                                       peer-[&:not(:placeholder-shown)]:-translate-y-4
                                       peer-[&:not(:placeholder-shown)]:text-xs
                                       peer-[&:not(:placeholder-shown)]:text-purple-600"
                            >
                                Nomor HP
                            </label>
                        </div>


                        <button type="submit" id="submit-btn"
                                class="w-full py-4 rounded-xl text-lg font-bold text-white
                                       bg-gradient-to-r from-purple-600 to-indigo-600
                                       hover:from-purple-700 hover:to-indigo-700
                                       shadow-lg hover:shadow-xl transition-all duration-300
                                       flex items-center justify-center gap-2 group disabled:opacity-60 disabled:cursor-not-allowed mt-2">
                            <i class="fas fa-paper-plane group-hover:translate-x-1 transition-transform"></i>
                            Kirim Pendaftaran
                        </button>

                        <p class="text-center text-sm text-gray-500 mt-3">
                            Dengan mendaftar, Anda setuju dengan
                            <a href="#syarat" class="text-purple-700 hover:underline font-medium">Syarat & Ketentuan</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>

        <!-- 4. FITUR / KEUNGGULAN -->
        <section id="keunggulan" class="py-20 bg-gradient-to-b from-white to-purple-50/30">
            <div class="container mx-auto px-6">
                <h2 class="section-title text-center mb-14 text-purple-900">Kenapa Harus Pilih MyRepublic?</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
                    @foreach($features as $feature)
                    <div class="card text-center p-8" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="feature-icon">
                            <!-- Update di sini: tambah prefix fa-solid fa- jika belum ada -->
                            <i class="{{ str_starts_with($feature->icon ?? '', 'fa-') ? $feature->icon : 'fa-solid fa-' . $feature->icon ?? 'check' }}"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-4 text-purple-900">{{ $feature->title }}</h3>
                        <p class="text-gray-600 text-base leading-relaxed">{{ $feature->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 5. PAKET (carousel seperti banner) -->
        <section id="paket" class="py-16 md:py-20 bg-gradient-to-b from-purple-50/50 to-white">
            <div class="container mx-auto px-6">
                <h2 class="text-3xl md:text-4xl font-bold text-center text-purple-900 mb-4">
                    Pilih Paket Internet Anda
                </h2>
                <p class="text-center text-gray-600 mb-8 max-w-3xl mx-auto text-lg">
                    Internet unlimited tanpa kuota, simetris upload & download, cocok untuk keluarga & kerja.
                </p>
                <!-- Tab Kategori -->
                <div class="flex justify-center mb-6">
                    <div class="tabs tabs-boxed bg-purple-50/60 rounded-full shadow-sm tab-container">
                        @foreach($categories as $category)
                        <a class="tab tab-md {{ $loop->first ? 'active' : '' }} font-medium text-purple-800 cursor-pointer"
                           data-category="{{ $category->id }}">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                <!-- Info Category (judul dan subjudul dinamis) -->
                <div id="categoryInfo" class="text-center mt-4 mb-6 px-4">
                    <h3 id="categoryDescription" class="text-2xl md:text-3xl font-bold text-purple-900 mb-2"></h3>
                    <p id="categorySubtitle" class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto"></p>
                </div>
                <!-- Tab Promo -->
                <div id="promoTabs" class="flex justify-center mb-5 hidden">
                    <div class="tabs tabs-boxed bg-orange-50/60 rounded-full shadow-sm tab-container"></div>
                </div>
                <!-- Tab Type -->
                <div id="typeTabs" class="flex justify-center mb-5">
                    <div class="tabs tabs-boxed bg-blue-50/60 rounded-full shadow-sm tab-container"></div>
                </div>
                <!-- Tab Streaming -->
                <div id="streamingTabs" class="flex justify-center mb-8 hidden">
                    <div class="tabs tabs-boxed bg-green-50/60 rounded-full shadow-sm tab-container"></div>
                </div>
                <!-- Carousel Container -->
                <div id="packageCarousel" class="relative w-full overflow-hidden rounded-3xl shadow-2xl bg-white/80 border border-purple-200/50">
                    <div id="packageTrack" class="flex overflow-x-auto snap-x snap-mandatory scrollbar-hide gap-4 md:gap-6 px-4 md:px-8 py-8 md:py-10 scroll-smooth">
                        <!-- Kartu paket dirender via JS -->
                    </div>
                    <!-- Tombol Navigasi -->
                    <button id="prevPkgBtn" class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 md:w-14 md:h-14 rounded-full bg-purple-800/70 backdrop-blur-md text-white text-2xl hover:bg-purple-900 transition shadow-lg flex items-center justify-center">
                        ❮
                    </button>
                    <button id="nextPkgBtn" class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-12 h-12 md:w-14 md:h-14 rounded-full bg-purple-800/70 backdrop-blur-md text-white text-2xl hover:bg-purple-900 transition shadow-lg flex items-center justify-center">
                        ❯
                    </button>
                </div>
                <!-- Loading -->
                <div id="loading" class="text-center py-16 hidden">
                    <span class="loading loading-spinner loading-lg text-purple-600"></span>
                    <p class="mt-4 text-gray-600 font-medium">Memuat paket terbaik untuk Anda...</p>
                </div>
            </div>
        </section>

        <!-- 6. SYARAT & KETENTUAN -->
        <section id="syarat" class="py-20 bg-gradient-to-b from-purple-50/50 to-white">
            <div class="container mx-auto px-6">
                @if($term)
                <div
                    class="group relative rounded-[2.25rem]
                           bg-white/80 backdrop-blur-xl
                           border border-slate-200/60
                           shadow-[0_30px_80px_-40px_rgba(0,0,0,0.35)]
                           hover:shadow-[0_50px_140px_-40px_rgba(99,102,241,0.45)]
                           transition-all duration-700 ease-out
                           hover:-translate-y-2">
                    <!-- glow accent -->
                    <div
                        class="absolute -inset-px rounded-[2.25rem]
                               opacity-0 group-hover:opacity-100
                               transition-opacity duration-700
                               bg-gradient-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20
                               blur-xl pointer-events-none">
                    </div>
                    <!-- Header -->
                    <div
                        class="relative px-14 py-10
                               bg-gradient-to-r from-indigo-600 via-purple-600 to-fuchsia-600
                               overflow-hidden rounded-t-[2.25rem]">
                        <!-- animated light -->
                        <div
                            class="absolute inset-0 opacity-0 group-hover:opacity-100
                                   transition-opacity duration-700
                                   bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.25),transparent_60%)]">
                        </div>
                        <h2 class="relative text-4xl font-semibold text-white tracking-tight">
                            Syarat & Ketentuan
                        </h2>
                        <p class="relative mt-2 text-white/80 text-sm max-w-xl">
                            Mohon dibaca dengan seksama sebelum melanjutkan proses berikutnya
                        </p>
                    </div>
                    <!-- Content -->
                    <div class="relative px-10 py-12 bg-white/90 rounded-b-[2.25rem]">
                        @if($term->is_active && $term->content)
                            <article
                                class="prose prose-slate max-w-none
                                       prose-headings:font-semibold
                                       prose-p:leading-8
                                       prose-li:leading-7
                                       prose-strong:text-slate-800">
                                {!! $term->content !!}
                            </article>
                        @else
                            <div
                                class="rounded-2xl bg-amber-50/80
                                       border border-amber-200
                                       px-6 py-4 text-amber-700">
                                Syarat & Ketentuan belum aktif atau belum tersedia.
                            </div>
                        @endif
                    </div>
                </div>
                @else
                    <div class="text-center text-slate-500 py-12">
                        Tidak ada data syarat & ketentuan yang aktif.
                    </div>
                @endif
            </div>
        </section>

        <!-- 7. TV ADDON + CHANNELS (Refined Modern) -->
        <section id="hiburan" class="py-20 bg-gradient-to-b from-purple-50/50 to-white overflow-hidden">
            <div class="container mx-auto px-6 relative">
                <!-- Floating decorative elements -->
                <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-b from-purple-50/50 to-white rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-b from-purple-50/50 to-white rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
                
                <!-- Hero Card -->
                <div class="relative mx-auto max-w-7xl bg-white/90 backdrop-blur-2xl border border-white/50 rounded-3xl shadow-2xl shadow-purple-500/20 overflow-hidden
                    transition-all duration-500 hover:shadow-purple-500/40">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 px-8 py-12 lg:px-16 lg:py-16 items-center">
                        <!-- Device Image - Order first on mobile, second on lg -->
                        <div class="relative flex justify-center items-center order-first lg:order-last">
                            @if($tv_addon->device_image)
                                <div class="relative z-10 transform transition-all duration-500 hover:rotate-y-6 hover:scale-105">
                                    <img
                                        src="{{ asset('storage/' . $tv_addon->device_image) }}"
                                        alt="TV Addon Device"
                                        class="max-w-full rounded-3xl shadow-2xl shadow-gray-800/20 object-cover">
                                    <!-- Glow effect -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 rounded-3xl blur-xl opacity-0 transition-opacity duration-300 hover:opacity-100"></div>
                                </div>
                            @else
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-dashed border-purple-300 rounded-3xl w-full max-w-md h-80 flex items-center justify-center transform transition-all duration-300 hover:scale-105">
                                    <p class="text-gray-500 text-center px-6 font-medium">
                                        Belum ada gambar device<br>
                                        Upload di pengaturan TV Add-on
                                    </p>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Text Content - Order last on mobile, first on lg -->
                        <div class="space-y-6 order-last lg:order-first">
                            <p class="text-purple-600 text-sm font-semibold tracking-wide uppercase animate-fade-in-up">
                                Akses Hiburan Terlengkap
                            </p>
                            <h2 class="text-4xl lg:text-6xl font-black text-gray-900 leading-tight tracking-tight animate-fade-in-up delay-100">
                                {{ $tv_addon->title ?? 'Hiburan Terlengkap Untuk Keluarga' }}
                            </h2>
                            <p class="text-xl text-gray-700 max-w-lg leading-relaxed animate-fade-in-up delay-200">
                                {{ $tv_addon->subtitle ?? 'Nikmati channel dan platform video streaming terlengkap' }}
                            </p>
                            <!-- Price Block -->
                            <div class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl px-8 py-6 text-white shadow-xl shadow-purple-500/30 transform transition-all duration-300 hover:scale-105 animate-fade-in-up delay-300">
                                <p class="text-sm opacity-90 mb-1 font-medium">Mulai dari</p>
                                <p class="text-4xl font-extrabold leading-none">
                                    @if($tv_addon->price_text)
                                        {{ $tv_addon->price_text }}
                                    @else
                                        Rp {{ number_format($tv_addon->price ?? 20000, 0, ',', '.') }}
                                        <span class="text-lg font-medium opacity-90">/bulan</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Channel Auto Scroll (Looping) -->
                @if($channels->count() > 0)
                    <div class="mt-24 overflow-hidden">
                        <p class="text-center text-purple-600 text-lg font-semibold tracking-wide mb-12 animate-fade-in-up">
                            Termasuk 70+ Channel & Platform
                        </p>
                        <div class="relative">
                            <!-- Gradient overlays for smooth edges -->
                            <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-indigo-50 to-transparent z-10 pointer-events-none"></div>
                            <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-pink-50 to-transparent z-10 pointer-events-none"></div>
                            
                            <div class="flex w-max gap-6 md:gap-8 animate-channel-loop">
                                @foreach($channels->concat($channels) as $channel)
                                    <div class="flex-shrink-0 group">
                                        <div class="w-20 h-20 md:w-24 md:h-24 bg-white/80 backdrop-blur-md rounded-2xl border border-white/50 shadow-lg shadow-purple-300/20 flex items-center justify-center p-4 transition-all duration-300 group-hover:shadow-purple-500/40 group-hover:scale-105 group-hover:-translate-y-1">

                                            @php
                                                if ($channel->logo) {
                                                    $logo = str_starts_with($channel->logo, 'http')
                                                        ? $channel->logo
                                                        : asset('storage/' . $channel->logo);
                                                } else {
                                                    $logo = null;
                                                }
                                            @endphp

                                            @if($logo)
                                                <img src="{{ $logo }}"
                                                     alt="{{ $channel->name }}"
                                                     class="max-h-full object-contain transition-transform duration-300 group-hover:rotate-3">
                                            @else
                                                <span class="text-xs md:text-sm text-gray-600 font-medium text-center leading-tight">
                                                    {{ $channel->name }}
                                                </span>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <!-- 8. FOOTER (tetap sama persis seperti asli) -->
        <footer class="bg-gradient-to-r from-purple-900 to-indigo-900 text-white pt-16 pb-10">
            <div class="absolute top-0 left-0 right-0 h-24 bg-white/5 rounded-b-3xl"></div>
            <div class="container mx-auto px-6 relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8 mb-12">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-amber-400 rounded-xl flex items-center justify-center text-2xl font-bold shadow-lg">MR</div>
                        <div>
                            <h1 class="text-3xl font-bold">MyRepublic</h1>
                            <p class="text-purple-200 text-sm">Internet Cepat • Stabil • Tanpa Batas</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="#" class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <a href="#" class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-12">
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-headset"></i> Layanan Pelanggan
                        </h3>
                        <ul class="space-y-2 text-purple-200">
                            <li>☎️ <span class="font-medium">1500-123</span> (24/7)</li>
                            <li>📧 cs@myrepublic.co.id</li>
                            <li>💬 Live Chat (08.00–22.00)</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt"></i> Kantor Pusat
                        </h3>
                        <address class="not-italic text-purple-200">
                            Gedung Telkom, Jl. Jend. Gatot Subroto No. 24<br>
                            Jakarta Selatan 12710
                        </address>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-envelope"></i> Langganan Newsletter
                        </h3>
                        <form class="flex gap-2">
                            <input type="email" placeholder="Email Anda" class="px-4 py-2.5 rounded-lg text-sm w-full text-gray-800">
                            <button class="bg-white text-purple-700 px-4 rounded-lg font-medium hover:bg-gray-100 transition">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </form>
                        <p class="text-xs text-purple-300 mt-2">Dapatkan promo & tips teknologi setiap minggu.</p>
                    </div>
                </div>

                <div class="pt-8 border-t border-purple-800/30 text-center text-purple-300 text-sm">
                    <p>© {{ date('Y') }} MyRepublic Indonesia. All rights reserved.</p>
                    <p class="mt-1">Proudly Indonesian • Berizin Kominfo No. 1234/PI/BPPT/2025</p>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init({ duration: 1000, once: true });
            // Setup global CSRF untuk semua AJAX request
            $.ajaxSetup({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const $addressInput = $('#address');
            const $detailGroup = $('#address-detail-group');
            
            // Fungsi debounce (untuk delay eksekusi)
            function debounce(func, delay) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), delay);
                };
            }
            
            // Handler dengan debounce 300ms
            const handleAddressChange = debounce(function () {
                const hasValue = $(this).val().trim().length > 0;
                
                if (hasValue) {
                    if ($detailGroup.hasClass('hidden')) {
                        $detailGroup.removeClass('hidden').hide().slideDown(300);
                    }
                } else {
                    $detailGroup.slideUp(300, function () {
                        $(this).addClass('hidden');
                    });
                }
            }, 300);  // Delay 300ms, bisa diubah ke 200 atau 500 sesuai selera
            
            // Bind event input
            $addressInput.on('input', handleAddressChange);
            
            // Cek awal kalau ada value (misal setelah refresh)
            if ($addressInput.val().trim().length > 0) {
                $detailGroup.removeClass('hidden');
            }

            $(document).ready(function() {
                $('#registration-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    const $form = $(this);
                    const $submitBtn = $form.find('button[type="submit"]');
                    const originalBtnText = $submitBtn.html();

                    // Loading state
                    $submitBtn.prop('disabled', true)
                              .html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'register_lead'})
                            .then(function(token) {
                                $('#recaptcha-token').val(token);

                                $.ajax({
                                    url: '{{ route('leads.store') }}',
                                    method: 'POST',
                                    data: $form.serialize(),
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Berhasil!',
                                                text: response.message || 'Pendaftaran berhasil!',
                                                timer: 2800,
                                                showConfirmButton: false,
                                                allowOutsideClick: false
                                            }).then(() => {
                                                if (response.wa_link) {
                                                    window.location.href = response.wa_link;
                                                }
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Gagal',
                                                text: response.message || 'Terjadi kesalahan. Silakan coba lagi.'
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        let errorMsg = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
                                        
                                        if (xhr.responseJSON) {
                                            if (xhr.responseJSON.message) {
                                                errorMsg = xhr.responseJSON.message;
                                            }
                                            
                                            // Tampilkan error per field
                                            if (xhr.responseJSON.errors) {
                                                const errors = xhr.responseJSON.errors;
                                                
                                                // Reset semua error dulu
                                                $('.text-red-600').addClass('hidden').text('');
                                                
                                                Object.keys(errors).forEach(field => {
                                                    const errorElement = $(`#${field}-error`);
                                                    if (errorElement.length) {
                                                        errorElement.text(errors[field][0]).removeClass('hidden');
                                                    }
                                                });
                                                
                                                errorMsg = 'Mohon lengkapi data yang wajib diisi.';
                                            }
                                        }
                                        
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            html: errorMsg,
                                            confirmButtonColor: '#8b5cf6'
                                        });
                                    },
                                    complete: function() {
                                        $submitBtn.prop('disabled', false).html(originalBtnText);
                                    }
                                });
                            })
                            .catch(function(error) {
                                console.error('reCAPTCHA error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: 'Verifikasi reCAPTCHA gagal. Silakan refresh halaman.'
                                });
                                $submitBtn.prop('disabled', false).html(originalBtnText);
                            });
                    });
                });
            });

            // Banner Slider
            document.addEventListener('DOMContentLoaded', () => {
                const carousel = document.getElementById('bannerCarousel');
                const track = document.getElementById('bannerTrack');
                const items = track.querySelectorAll('.banner-item');
                const indicators = document.querySelectorAll('.indicator');
                if (!carousel || items.length <= 1) return;
                const total = items.length;
                let currentIndex = 0;
                let autoTimer = null;
                const intervalTime = 5000;
                function slideWidth() {
                    return carousel.clientWidth;
                }
                function goTo(index, smooth = true) {
                    currentIndex = (index + total) % total;
                    const target = currentIndex * slideWidth();
                    carousel.scrollTo({
                        left: target,
                        behavior: smooth ? 'smooth' : 'auto'
                    });
                    // 🔍 DEBUG (INI SEKARANG AKAN BERUBAH)
                    console.log('[goTo]', {
                        index: currentIndex,
                        targetLeft: target,
                        actualScrollLeft: carousel.scrollLeft
                    });
                    updateIndicators();
                }
                function updateIndicators() {
                    indicators.forEach((dot, i) => {
                        dot.classList.toggle('bg-gray-900', i === currentIndex);
                        dot.classList.toggle('scale-125', i === currentIndex);
                    });
                }
                function next() { goTo(currentIndex + 1); }
                function prev() { goTo(currentIndex - 1); }
                function startAuto() {
                    stopAuto();
                    autoTimer = setInterval(next, intervalTime);
                    console.log('[auto] started');
                }
                function stopAuto() {
                    if (autoTimer) {
                        clearInterval(autoTimer);
                        autoTimer = null;
                        console.log('[auto] stopped');
                    }
                }
                // Buttons
                carousel.querySelector('.next-btn').addEventListener('click', () => {
                    stopAuto(); next(); startAuto();
                });
                carousel.querySelector('.prev-btn').addEventListener('click', () => {
                    stopAuto(); prev(); startAuto();
                });
                // Dots
                indicators.forEach((dot, i) => {
                    dot.addEventListener('click', () => {
                        stopAuto();
                        goTo(i);
                        startAuto();
                    });
                });
                // Sync on swipe
                carousel.addEventListener('scroll', () => {
                    const idx = Math.round(carousel.scrollLeft / slideWidth());
                    if (idx !== currentIndex) {
                        currentIndex = idx;
                        updateIndicators();
                        console.log('[sync]', idx);
                    }
                });
                carousel.addEventListener('mouseenter', stopAuto);
                carousel.addEventListener('mouseleave', startAuto);
                window.addEventListener('resize', () => {
                    goTo(currentIndex, false);
                });
                // INIT
                goTo(0, false);
                startAuto();
            });
            document.addEventListener('DOMContentLoaded', () => {
                console.log('[INIT] Mulai auto-filter cascade');
                renderPackages(allPackages); // optional: render dulu semua (sementara)
                if (categories.length === 0) {
                    console.warn('Tidak ada kategori');
                    renderPackages([]);
                    return;
                }
                const firstCategory = categories[0];
                const firstCatId = firstCategory.id;
                // Aktifkan tab kategori pertama
                activateTab('.tabs', firstCatId, 'data-category');
                // Cascade
                updatePromoTabs(firstCatId);
                const firstPromo = promosByCategory[firstCatId]?.[0];
                const firstPromoId = firstPromo ? firstPromo.id : null;
                updateTypeTabs(firstCatId, firstPromoId);
                updateStreamingTabs();
                filterPackages();
                // console.log('[INIT] Selesai auto-filter:');
                // console.log(' - Kategori:', firstCatId);
                // console.log(' - Promo :', firstPromoId || '-');
                // console.log(' - Type :', getActiveValue('#typeTabs .tabs', 'type') || '-');
                // console.log(' - Addon :', shouldShowStreamingTabs() ? getActiveValue('#streamingTabs .tabs', 'addon') : '-');
            });

            // Fungsi untuk update arrows berdasarkan scroll position (panggil di semua tab-container)
            function updateTabArrows(container) {
                const isAtStart = container.scrollLeft <= 0;
                const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 1;
                container.classList.toggle('at-start', isAtStart);
                container.classList.toggle('at-end', isAtEnd);
            }
            // Init untuk semua tab-container
            document.querySelectorAll('.tab-container').forEach(cont => {
                cont.addEventListener('scroll', () => updateTabArrows(cont));
                updateTabArrows(cont); // Init
            });
            // ── DATA DARI BLADE ──────────────────────────────────────────────────────────────
            const allPackages = {!! $packages->map(function ($package) {
                return [
                    'id' => $package->id,
                    'name' => $package->name ?? 'Nama Paket',
                    'base_price' => $package->base_price ?? 0,
                    'speed_mbps' => $package->speed_mbps ?? 0,
                    'speed_up_to_mbps' => $package->speed_up_to_mbps ?? null,
                    'promo_label' => $package->promo_label ?? null,
                    'has_tv' => $package->has_tv ?? false,
                    'channel_count' => $package->channel_count ?? null,
                    'stb_info' => $package->stb_info ?? null,
                    'category_id' => $package->category_id,
                    'package_type_id' => $package->package_type_id,
                    'image' => $package->image
                        ? asset('storage/' . $package->image)
                        : asset('images/default-package-header.jpg'),
                    'streaming_addons' => $package->relationLoaded('streamingAddons')
                                            ? $package->streamingAddons->pluck('name')->toArray()
                                            : [],
                    'benefits' => $package->relationLoaded('benefits')
                                            ? $package->benefits->map(fn($b) => trim($b->name . ' ' . ($b->pivot->duration_value ?? '') . ' ' . ($b->pivot->duration_unit ?? '')))->toArray()
                                            : [],
                    'features' => $package->relationLoaded('features')
                                            ? $package->features->map(fn($f) => ['label' => $f->label, 'icon' => $f->icon ?? 'check'])->toArray()
                                            : [],
                ];
            })->toJson() !!};
            const categories = {!! $categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'description' => $c->description, 'subtitle' => $c->subtitle])->toJson() !!};
            const promosByCategory = {!! $promosByCategory->toJson() !!};
            const typesByCategory = {!! $typesByCategory->toJson() !!};
            const typesByPromo = {!! json_encode($typesByPromo ?? []) !!};
            const streamingAddons = {!! $streamingAddons->map(fn($a) => ['id' => $a->id, 'name' => $a->name])->toJson() !!};
            const addonsByPackage = {!! json_encode($addonsByPackage ?? []) !!};
            // ── HELPER ───────────────────────────────────────────────────────────────────────
            function activateTab(selector, value, dataAttr) {
                document.querySelectorAll(`${selector} .tab`).forEach(tab => {
                    tab.classList.toggle('active', tab.getAttribute(dataAttr) === String(value));
                });
            }
            function getActiveValue(selector, dataKey) {
                let active = document.querySelector(`${selector} .tab.active`);
                if (active) return active.dataset[dataKey];
                const first = document.querySelector(`${selector} .tab`);
                if (first) {
                    first.classList.add('active');
                    return first.dataset[dataKey];
                }
                return null;
            }
            function shouldShowStreamingTabs() {
                const typeId = getActiveValue('#typeTabs .tabs', 'type');
                if (!typeId) return false;
                const catId = getActiveValue('.tabs', 'category');
                if (!catId) return false;
                const types = typesByCategory[catId] || [];
                const typeObj = types.find(t => String(t.id) === String(typeId));
                return typeObj && Number(typeObj.supports_streaming_addons ?? 0) === 1;
            }
            // ── UPDATE TABS ──────────────────────────────────────────────────────────────────
            function updatePromoTabs(categoryId) {
                const container = document.querySelector('#promoTabs .tabs');
                if (!container) return;
                container.innerHTML = '';
                const promos = promosByCategory[categoryId] || [];
                if (promos.length === 0) {
                    document.getElementById('promoTabs').classList.add('hidden');
                    return;
                }
                promos.forEach((promo, i) => {
                    const tab = document.createElement('a');
                    tab.className = `tab tab-lg font-bold text-orange-900 px-6 py-3 cursor-pointer ${i === 0 ? 'active' : ''}`;
                    tab.dataset.promo = promo.id;
                    tab.textContent = promo.name;
                    tab.addEventListener('click', () => filterByPromo(promo.id));
                    container.appendChild(tab);
                });
                document.getElementById('promoTabs').classList.remove('hidden');
            }
            function updateTypeTabs(categoryId, promoId = null) {
                const container = document.querySelector('#typeTabs .tabs');
                if (!container) return;
                container.innerHTML = '';
                let types = typesByCategory[categoryId] || [];
                if (promoId) {
                    const allowed = typesByPromo[promoId] || [];
                    types = types.filter(t => allowed.includes(Number(t.id)));
                }
                if (types.length === 0) {
                    document.getElementById('typeTabs')?.classList.add('hidden');
                    return;
                }
                types.forEach((type, i) => {
                    const tab = document.createElement('a');
                    tab.className = `tab tab-lg font-bold text-blue-900 px-6 py-3 cursor-pointer ${i === 0 ? 'active' : ''}`;
                    tab.dataset.type = type.id;
                    tab.textContent = type.name;
                    tab.addEventListener('click', () => {
                        activateTab('#typeTabs .tabs', type.id, 'data-type');
                        updateStreamingTabs();
                        filterPackages();
                    });
                    container.appendChild(tab);
                });
                if (types.length > 0) activateTab('#typeTabs .tabs', types[0].id, 'data-type');
                document.getElementById('typeTabs').classList.remove('hidden');
            }
            function updateStreamingTabs() {
                const wrapper = document.getElementById('streamingTabs');
                const container = wrapper?.querySelector('.tabs');
                if (!container) return;
                container.innerHTML = '';
                if (!shouldShowStreamingTabs() || streamingAddons.length === 0) {
                    wrapper.classList.add('hidden');
                    filterPackages();
                    return;
                }
                wrapper.classList.remove('hidden');
                streamingAddons.forEach((addon, i) => {
                    const tab = document.createElement('a');
                    tab.className = `tab tab-lg font-bold text-green-900 px-6 py-3 cursor-pointer ${i === 0 ? 'active' : ''}`;
                    tab.dataset.addon = addon.id;
                    tab.textContent = addon.name;
                    tab.addEventListener('click', () => filterByStreamingAddon(addon.id));
                    container.appendChild(tab);
                });
                if (streamingAddons.length > 0) activateTab('#streamingTabs .tabs', streamingAddons[0].id, 'data-addon');
                filterPackages();
            }
            // ── FILTER ───────────────────────────────────────────────────────────────────────
            function updateCategoryInfo(categoryId) {
                const category = categories.find(c => String(c.id) === String(categoryId));
                if (!category) return;
                
                const descElem = document.getElementById('categoryDescription');
                const subElem = document.getElementById('categorySubtitle');
                const infoElem = document.getElementById('categoryInfo');
                
                if (descElem && subElem && infoElem) {
                    descElem.textContent = category.description || ''; // Judul section
                    subElem.textContent = category.subtitle || ''; // Subjudul
                    infoElem.classList.add('visible'); // Animasi fade-in
                }
            }
            function filterByCategory(categoryId) {
                activateTab('.tabs', categoryId, 'data-category');
                updateCategoryInfo(categoryId);
                updatePromoTabs(categoryId);
                const firstPromoId = promosByCategory[categoryId]?.[0]?.id ?? null;
                updateTypeTabs(categoryId, firstPromoId);
                updateStreamingTabs();
                filterPackages();
            }
            function filterByPromo(promoId) {
                activateTab('#promoTabs .tabs', promoId, 'data-promo');
                const catId = getActiveValue('.tabs', 'category');
                updateTypeTabs(catId, promoId);
                updateStreamingTabs();
                filterPackages();
            }
            function filterByType(typeId) {
                activateTab('#typeTabs .tabs', typeId, 'data-type');
                updateStreamingTabs();
                filterPackages();
            }
            function filterByStreamingAddon(addonId) {
                activateTab('#streamingTabs .tabs', addonId, 'data-addon');
                filterPackages();
            }
            function filterPackages() {
                let filtered = allPackages;
                const catId = getActiveValue('.tabs', 'category');
                if (catId) filtered = filtered.filter(p => String(p.category_id) === String(catId));
                const promoId = getActiveValue('#promoTabs .tabs', 'promo');
                if (promoId) {
                    const allowed = typesByPromo[promoId] || [];
                    filtered = filtered.filter(p => allowed.includes(Number(p.package_type_id)));
                }
                const typeId = getActiveValue('#typeTabs .tabs', 'type');
                if (typeId) filtered = filtered.filter(p => String(p.package_type_id) === String(typeId));
                if (shouldShowStreamingTabs()) {
                    const addonId = getActiveValue('#streamingTabs .tabs', 'addon');
                    if (addonId) {
                        filtered = filtered.filter(p => {
                            const addons = addonsByPackage[p.id] || [];
                            return addons.includes(Number(addonId));
                        });
                    }
                }
                renderPackages(filtered);
            }
            // ── MULTI-ITEM CAROUSEL LOGIC ────────────────────────────────────────────────────
            function scrollToPackage(direction) {
                const track = document.getElementById('packageTrack');
                if (!track) return;
                const cards = track.querySelectorAll('.package-card');
                if (cards.length === 0) return;
                const cardWidth = cards[0].offsetWidth + parseFloat(getComputedStyle(track).gap);
                let scrollAmount = track.scrollLeft;
                if (direction === 'next') {
                    scrollAmount += cardWidth;
                } else {
                    scrollAmount -= cardWidth;
                }
                scrollAmount = Math.max(0, Math.min(scrollAmount, track.scrollWidth - track.clientWidth));
                track.scrollTo({ left: scrollAmount, behavior: 'smooth' });
            }

            let autoInterval = null;
            function startAutoScroll() {
                clearInterval(autoInterval);
                autoInterval = setInterval(() => {
                    const track = document.getElementById('packageTrack');
                    if (!track) return;
                    const cards = track.querySelectorAll('.package-card');
                    if (cards.length === 0) return;
                    const cardWidth = cards[0].offsetWidth + parseFloat(getComputedStyle(track).gap);
                    let nextScroll = track.scrollLeft + cardWidth;
                    if (nextScroll >= track.scrollWidth - track.clientWidth - 10) {
                        track.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        track.scrollTo({ left: nextScroll, behavior: 'smooth' });
                    }
                }, 5000);
            }

            function stopAutoScroll() {
                clearInterval(autoInterval);
            }

            function updateNavButtons() {
                const track = document.getElementById('packageTrack');
                const prevBtn = document.getElementById('prevPkgBtn');
                const nextBtn = document.getElementById('nextPkgBtn');
                if (!track || !prevBtn || !nextBtn) return;
                const hasOverflow = track.scrollWidth > track.clientWidth;
                prevBtn.classList.toggle('hidden', !hasOverflow);
                nextBtn.classList.toggle('hidden', !hasOverflow);
                prevBtn.disabled = track.scrollLeft <= 0;
                nextBtn.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1; // Toleransi floating point
                prevBtn.classList.toggle('disabled', prevBtn.disabled);
                nextBtn.classList.toggle('disabled', nextBtn.disabled);
            }

            function renderPackages(packages) {
                const track = document.getElementById('packageTrack');
                const loading = document.getElementById('loading');

                if (!track) return;
                track.innerHTML = '';
                loading?.classList.add('hidden');
                if (packages.length === 0) {
                    track.innerHTML = '<div class="w-full py-20 text-center text-gray-600 text-xl">Paket lain akan segera hadir!</div>';
                    track.classList.remove('justify-center');
                    updateNavButtons();
                    return;
                }
                packages.forEach(pkg => {
                    const card = document.createElement('div');
                    card.className = 'package-card relative bg-white rounded-3xl shadow-2xl border border-purple-200/50 overflow-hidden flex flex-col h-full min-h-[520px] md:min-h-[620px] transition-all duration-300 hover:shadow-[0_20px_60px_rgba(124,58,237,0.2)] hover:-translate-y-1';

                    // === LOGIKA TAMPILAN SPEED DENGAN CORETAN ===
                    let speedDisplay = '';

                    if (pkg.speed_up_to_mbps && pkg.speed_up_to_mbps > pkg.speed_mbps) {
                        // ~~150 Mbps~~ 250 Mbps — lebih kecil lagi
                        speedDisplay = `
                            <s class="text-sm md:text-base text-gray-500">${pkg.speed_up_to_mbps} Mbps</s>
                            <span class="text-base md:text-lg font-semibold text-purple-900">${pkg.speed_mbps} Mbps</span>
                        `;
                    } else if (pkg.speed_up_to_mbps && pkg.speed_up_to_mbps !== pkg.speed_mbps) {
                        // 250 Mbps / 150 Mbps
                        speedDisplay = `
                            <span class="text-base md:text-lg font-semibold text-purple-900">${pkg.speed_mbps} Mbps</span>
                            <span class="text-sm md:text-base text-purple-700"> / ${pkg.speed_up_to_mbps} Mbps</span>
                        `;
                    } else {
                        // Normal tanpa promo
                        speedDisplay = `
                            <span class="text-base md:text-lg font-semibold text-purple-900">${pkg.speed_mbps} Mbps</span>
                        `;
                    }

                    card.innerHTML = `
                        <div class="fixed-top flex-shrink-0 min-h-[320px]"> <!-- Min-height untuk konsistensi, agar features tidak naik -->
                            <!-- Header modern dengan gradient overlay dan gambar blurred -->
                            <div class="relative h-24 md:h-32 overflow-hidden">
                                <img src="${pkg.image || '/path/to/default-header.jpg'}" alt="${pkg.name}" class="w-full h-full object-cover scale-105 transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-b from-transparent to-purple-900/70 mix-blend-multiply"></div>
                                <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_120%,rgba(167,139,250,0.15),transparent)]"></div>
                            </div>
                            <!-- Nama paket dengan font modern dan centered -->
                            <div class="px-4 py-2 md:py-2.5 text-center border-b border-purple-100/50 bg-white/95 backdrop-blur-sm">
                                <p class="text-base md:text-lg font-bold text-purple-800 tracking-wide uppercase">
                                    ${pkg.name}
                                </p>
                            </div>
                            <!-- Speed dengan icon WiFi untuk keren -->
                            <div class="px-4 py-2 md:py-2.5 text-center border-b border-purple-100/50 flex items-center justify-center gap-2">
                                <i class="fas fa-wifi text-purple-600 text-sm md:text-base"></i>
                                <p class="flex items-center gap-1.5 font-semibold text-purple-900 whitespace-nowrap text-sm md:text-base">
                                    ${speedDisplay}
                                </p>
                            </div>
                            <!-- Harga dengan gradient text effect dan note kecil -->
                            <div class="h-16 px-4 py-2 text-center bg-gradient-to-b from-purple-50/30 to-white flex flex-col justify-center items-center">
                                <p class="text-lg md:text-xl font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 text-transparent bg-clip-text leading-tight whitespace-nowrap">
                                    Rp ${Number(pkg.base_price).toLocaleString('id-ID')}
                                    <span class="text-sm md:text-base font-bold">/Bulan</span>
                                </p>
                                <p class="text-gray-500 mt-0.5 text-xs italic">
                                    ${pkg.tax_included === true ? 'Sudah Termasuk PPN 11%' : 'Belum Termasuk PPN 11%'}
                                </p>
                            </div>
                            <!-- Channel info dengan badge-like style, atau spacer jika kosong-->
                            <!-- Klo pake kode ini,deskripsi di dlm card bisa sejajar, namun jika STD tidak ada maka mmbuat space kosong ckup lebar -->
                            <!--
                            ${pkg.channel_count && pkg.channel_count !== 0 && pkg.stb_info && pkg.stb_info !== '' && pkg.stb_info !== null ? `
                            <div class="h-16 p-2 bg-indigo-50/50 border-b border-indigo-100/50 flex flex-wrap justify-center items-center gap-2 text-xs text-indigo-800 font-medium">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-tv text-indigo-600"></i>
                                    Channel TV ${pkg.channel_count} Channel
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-box text-indigo-600"></i>
                                    ${pkg.stb_info}
                                </span>
                            </div>` : '<div class="h-16 border-b border-indigo-100/50"></div>'} -->

                            ${pkg.channel_count && pkg.channel_count > 0 && pkg.stb_info && pkg.stb_info.trim() !== '' ? `
                                <div class="h-16 p-2 bg-indigo-50/50 border-b border-indigo-100/50 flex flex-wrap justify-center items-center gap-2 text-xs text-indigo-800 font-medium">
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-tv text-indigo-600"></i>
                                        Channel TV ${pkg.channel_count} Channel
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i class="fas fa-box text-indigo-600"></i>
                                        ${pkg.stb_info}
                                    </span>
                                </div>
                            ` : '<div class="border-b border-indigo-100/50"></div>'}

                            <!-- Benefits sebagai badges modern dengan hover, atau spacer jika kosong -->
                            ${pkg.benefits?.length > 0 ? `
                            <div class="h-16 p-2 bg-emerald-50/50 border-b border-emerald-100/50 flex flex-wrap justify-center items-center gap-2 overflow-y-auto">
                                ${pkg.benefits.map(b => `<span class="badge bg-emerald-100 text-emerald-800 text-xs font-medium px-2 py-0.5 rounded-full shadow-sm hover:bg-emerald-200 transition-colors leading-none">${b}</span>`).join('')}
                            </div>` : '<div class="h-16 border-b border-emerald-100/50"></div>'}
                        </div>
                        <!-- Features scrollable dengan list modern -->
                        ${pkg.features?.length > 0 ? `
                        <div class="features-scroll relative p-4 md:p-5 pb-12 text-gray-700 text-xs overflow-y-auto flex-grow bg-white/95">
                            <ul class="flex flex-col justify-between h-full"> <!-- Ubah ke flex-col justify-between h-full agar item tersebar ke bawah, memanjangkan deskripsi untuk isi space kosong -->
                                ${pkg.features.map(f => `
                                    <li class="flex items-start gap-1.5 p-1 rounded-lg hover:bg-purple-50/50 transition-colors"> <!-- Kurangi gap ke 1.5 dan p ke 1 -->
                                        <div class="w-6 h-6 bg-purple-100 rounded-xl flex items-center justify-center shadow-md"> <!-- Kecilkan div ke w-6 h-6 -->
                                            <img src="https://raw.githubusercontent.com/tailwindlabs/heroicons/master/optimized/24/solid/${f.icon || 'check-circle'}.svg?sanitize=true" class="w-3 h-3 text-purple-600" alt="${f.label} icon"> <!-- Kecilkan img ke w-3 h-3 -->
                                        </div>
                                        <span class="leading-normal">${f.label}</span> <!-- Ubah ke leading-normal agar jarak antar baris tidak terlalu jauh jika multi-line -->
                                    </li>
                                `).join('')}
                            </ul>
                        </div>` : '<div class="flex-grow p-4 text-center text-gray-500 font-medium">Tidak ada fitur tambahan</div>'}
                    `;
                    track.appendChild(card);
                });

                track.scrollTo({ left: 0, behavior: 'instant' });
                if (packages.length === 4) {
                    track.classList.add('justify-between');  // Atau 'space-around' jika perlu
                } else if (packages.length < 4) {
                    track.classList.add('justify-center');
                } else {
                    track.classList.remove('justify-between', 'justify-center');
                }
                updateNavButtons();
            }

            // ── INISIALISASI ─────────────────────────────────────────────────────────────────
            document.addEventListener('DOMContentLoaded', () => {
                console.log('[INIT] Mulai auto-filter cascade');
                if (categories.length === 0) {
                    renderPackages([]);
                    return;
                }
                const firstCatId = categories[0].id;
                activateTab('.tabs', firstCatId, 'data-category');
                updateCategoryInfo(firstCatId);
                updatePromoTabs(firstCatId);
                const firstPromoId = promosByCategory[firstCatId]?.[0]?.id ?? null;
                updateTypeTabs(firstCatId, firstPromoId);
                updateStreamingTabs();
                filterPackages();
                // Carousel events
                document.getElementById('prevPkgBtn')?.addEventListener('click', () => scrollToPackage('prev'));
                document.getElementById('nextPkgBtn')?.addEventListener('click', () => scrollToPackage('next'));
                const carousel = document.getElementById('packageCarousel');
                if (carousel) {
                    carousel.addEventListener('mouseenter', stopAutoScroll);
                    carousel.addEventListener('mouseleave', startAutoScroll);
                    startAutoScroll();
                }
                const track = document.getElementById('packageTrack');
                track.addEventListener('scroll', updateNavButtons);
                window.addEventListener('resize', updateNavButtons);
                // Event delegation untuk tab
                document.addEventListener('click', e => {
                    const tab = e.target.closest('.tab');
                    if (!tab) return;
                    if (tab.dataset.category) filterByCategory(tab.dataset.category);
                    if (tab.dataset.promo) filterByPromo(tab.dataset.promo);
                    if (tab.dataset.type) filterByType(tab.dataset.type);
                    if (tab.dataset.addon) filterByStreamingAddon(tab.dataset.addon);
                });
            });
        </script>

    </body>
</html>