@extends('layouts.app')

@section('title', 'Manajemen Paket Internet')

@section('content')
<div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-8 md:p-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h3 class="text-3xl md:text-5xl font-extrabold">Manajemen Paket Internet</h3>
                <p class="text-purple-200 mt-4 text-base md:text-lg opacity-90">
                    Kelola paket untuk landing page. Benefit dari master, fitur custom, add-on streaming kondisional.
                </p>
            </div>
            <button onclick="addPackage()"
                    class="btn bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-4 px-8 rounded-2xl shadow-lg transform hover:scale-105 transition-all">
                <i class="fa-solid fa-plus mr-3"></i> Tambah Paket
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-8 md:p-10">
        <div class="card bg-base-100 shadow-xl border border-gray-200 rounded-2xl overflow-hidden">
            <div class="card-body p-0">
                <table id="packageTable" class="table table-lg w-full">
                    <thead class="bg-purple-50 text-purple-900">
                        <tr>
                            <th class="w-12">No</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Promo</th>
                            <th>Nama Paket</th>
                            <th class="text-center">Speed</th>
                            <th>Harga</th>
                            <th class="text-center">Status</th>
                            <th class="text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<dialog id="packageModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-6xl bg-white rounded-3xl shadow-2xl p-10">
        <div class="flex justify-between items-center mb-10 pb-6 border-b border-purple-100">
            <h3 class="text-4xl font-extrabold text-purple-900" id="modalTitle">Tambah Paket Baru</h3>
            <button class="btn btn-ghost btn-circle btn-lg text-3xl hover:bg-purple-50" onclick="packageModal.close()">✕</button>
        </div>

        <form id="packageForm" enctype="multipart/form-data" class="space-y-10">
            @csrf
            <input type="hidden" id="packageId">
            <input type="hidden" id="method" value="POST">

            <!-- 1. Kategori, Promo, Tipe -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 bg-gradient-to-br from-purple-50 to-indigo-50 p-8 rounded-2xl border border-purple-200 shadow-inner">
                <div>
                    <label class="label font-bold text-xl text-gray-800">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="categorySelect" class="select select-bordered select-lg w-full" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="paymentPromoWrapper" class="hidden">
                    <label class="label font-bold text-xl text-gray-800">Promo Pembayaran</label>
                    <select name="payment_promo_id" id="paymentPromoSelect" class="select select-bordered select-lg w-full">
                        <option value="">Tanpa Promo / Default</option>
                    </select>
                </div>

                <div id="packageTypeWrapper">
                    <label class="label font-bold text-xl text-gray-800">
                        Tipe Paket <span class="text-red-500">*</span>
                    </label>
                    <select name="package_type_id" id="packageTypeSelect" class="select select-bordered select-lg w-full" required>
                        <option value="">Pilih Tipe Paket</option>
                    </select>
                </div>
            </div>

            <!-- Streaming Add-ons -->
            <div id="streamingAddonsSection" class="hidden bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl border-2 border-purple-200 shadow-inner">
                <label class="label font-bold text-2xl text-purple-800 mb-6">Add-on Streaming Premium</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach($streamingAddons as $addon)
                        <label class="cursor-pointer group flex flex-col items-center gap-3 bg-white p-5 rounded-2xl border border-purple-200 hover:border-purple-400 hover:shadow-lg transition-all duration-300">
                            <input type="checkbox" name="streaming_addons[]" value="{{ $addon->id }}" class="checkbox checkbox-primary checkbox-lg">
                            <span class="text-center font-semibold text-gray-800 group-hover:text-purple-700 transition-colors">{{ $addon->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-sm text-purple-700 mt-6 italic text-center">
                    Centang add-on yang disertakan (hanya untuk tipe paket yang mendukung).
                </p>
            </div>

            <!-- Info Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="form-control">
                    <label class="label font-bold text-xl text-gray-800">
                        Nama Paket <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered input-lg w-full shadow-sm" required placeholder="contoh: Nexus 300/400">
                </div>

                <div class="form-control">
                    <label class="label font-bold text-xl text-gray-800">
                        Speed (Mbps) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-4">
                        <input 
                            type="number" 
                            name="speed_mbps" 
                            placeholder="300" 
                            class="input input-bordered input-lg w-full" 
                            required 
                            min="1"
                            value="{{ old('speed_mbps', $package->speed_mbps ?? '') }}"
                        >
                        <span class="text-gray-600 font-bold text-xl">/</span>
                        <input 
                            type="number" 
                            name="speed_up_to_mbps" 
                            placeholder="400 (opsional)" 
                            class="input input-bordered input-lg w-full" 
                            min="1"
                            value="{{ old('speed_up_to_mbps', $package->speed_up_to_mbps ?? '') }}"
                        >
                    </div>
                    <div class="label">
                        <span class="label-text-alt text-gray-500">
                            Isi kolom kedua hanya jika ada promo "dari ... menjadi ...".  
                            Contoh: 300/400 atau ~~400~~ 300 Mbps
                        </span>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label font-bold text-xl text-gray-800">
                        Harga Dasar (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="base_price" class="input input-bordered input-lg w-full" required min="0" placeholder="427350">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex items-center gap-6 bg-gradient-to-r from-purple-50 to-indigo-50 p-6 rounded-2xl border border-purple-100 shadow-sm">
                    <label class="cursor-pointer flex items-center gap-4">
                        <input type="hidden" name="tax_included" value="0">
                        <input type="checkbox" name="tax_included" value="1" class="checkbox checkbox-primary checkbox-lg" checked>
                        <span class="label-text font-bold text-xl text-gray-800">Sudah termasuk PPN 11%</span>
                    </label>
                </div>

                <div class="form-control">
                    <label class="label font-bold text-xl text-gray-800">Label Promo Manual (opsional)</label>
                    <input type="text" name="promo_label" class="input input-bordered input-lg w-full" placeholder="contoh: BAYAR 12 BLN GRATIS 6 BLN">
                </div>
            </div>

            <!-- TV + STB -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 p-8 rounded-2xl border border-red-200 shadow-sm">
                <label class="cursor-pointer flex items-center gap-4 mb-6">
                    <!-- Hidden default: tidak punya TV -->
                    <input type="hidden" name="has_tv" value="0">
                    
                    <!-- Checkbox aktifkan dengan value 1 -->
                    <input type="checkbox" id="has_tv" name="has_tv" value="1" 
                           class="checkbox checkbox-secondary checkbox-lg">
                    
                    <span class="text-2xl font-bold text-red-700">Sertakan Layanan TV (Channel + STB Android)</span>
                </label>
                
                <div id="tvSection" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="label font-bold text-lg">Jumlah Channel</label>
                        <input type="number" name="channel_count" class="input input-bordered w-full" min="1" value="76">
                    </div>
                    <div>
                        <label class="label font-bold text-lg">Info STB</label>
                        <input type="text" name="stb_info" class="input input-bordered w-full" value="Termasuk STB Android 12">
                    </div>
                </div>
            </div>

            <!-- Benefits -->
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <label class="label font-bold text-2xl text-success">Benefit & Layanan Tambahan</label>
                    <button type="button" id="addBenefitBtn" class="btn btn-success btn-outline btn-md gap-2 hover:scale-105 transition-transform">
                        <i class="fa-solid fa-plus"></i> Tambah Benefit
                    </button>
                </div>
                <div id="benefitsRepeater" class="space-y-6"></div>
            </div>

            <!-- Features -->
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <label class="label font-bold text-2xl text-warning">Fitur Standar Paket</label>
                    <button type="button" id="addFeatureBtn" class="btn btn-warning btn-outline btn-md gap-2 hover:scale-105 transition-transform">
                        <i class="fa-solid fa-plus"></i> Tambah Fitur
                    </button>
                </div>
                <div id="featuresRepeater" class="space-y-6"></div>
            </div>

            <!-- Gambar -->
            <div class="space-y-6">
                <label class="label font-bold text-2xl text-gray-800">Gambar Paket (rekomendasi 600×400)</label>
                <input type="file" name="image" class="file-input file-input-bordered file-input-primary w-full h-16" accept="image/*">
                <div id="imagePreview" class="mt-6 hidden">
                    <p class="text-base font-medium text-gray-700 mb-4">Pratinjau Gambar:</p>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border-4 border-purple-300">
                        <img id="previewImg" class="w-full h-80 object-cover" src="" alt="Preview">
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-between bg-gradient-to-r from-purple-50 to-indigo-50 p-8 rounded-2xl border border-purple-200 shadow-inner">
                <div>
                    <p class="font-bold text-2xl text-purple-800">Tampilkan di Landing Page</p>
                    <p class="text-base text-purple-700 mt-3">Paket akan muncul di carousel jika diaktifkan</p>
                </div>
                <div class="flex items-center gap-6">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="toggle toggle-primary toggle-lg" checked>
                </div>
            </div>

            <!-- Tombol -->
            <div class="modal-action flex justify-end gap-6 pt-10 border-t border-gray-200">
                <button type="button" class="btn btn-lg btn-ghost px-12 hover:bg-gray-100 transition" onclick="packageModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-lg px-14 shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all">
                    <i class="fa-solid fa-save mr-3 text-xl"></i> Simpan Paket
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>Tutup</button></form>
</dialog>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script>
    // Global variables
    const modal = document.getElementById('packageModal');
    const form = document.getElementById('packageForm');
    let table;
    let benefitCounter = 0;
    let featureCounter = 0;
    let allBenefits = [];

    // Elements
    const categorySelect       = document.getElementById('categorySelect');
    const packageTypeSelect    = document.getElementById('packageTypeSelect');
    const paymentPromoSelect   = document.getElementById('paymentPromoSelect');
    const paymentPromoWrapper  = document.getElementById('paymentPromoWrapper');

    // ─── Helper ─────────────────────────────────────────────────
    function showLoading(el, text = 'Memuat...') {
        el.innerHTML = `<option value="">${text}</option>`;
    }
    function showError(el, msg = 'Gagal memuat') {
        el.innerHTML = `<option value="">${msg}</option>`;
    }
    function renderPackageTypes(types) {
        const currentValue = packageTypeSelect.value; // simpan value sebelum rebuild
        packageTypeSelect.innerHTML = '<option value="">Pilih Tipe Paket</option>';
        (types || []).forEach(t => {
            const opt = new Option(t.name, t.id);
            opt.dataset.promoId = t.payment_promo_id || '';
            packageTypeSelect.appendChild(opt);
        });
        // Restore value setelah rebuild jika masih ada
        if (currentValue && packageTypeSelect.querySelector(`option[value="${currentValue}"]`)) {
            packageTypeSelect.value = currentValue;
        }
    }

    // ─── Load Promo & Tipe secara berurutan ────────────────────
    async function loadPromoAndTypes(categoryId) {
        if (!categoryId) {
            packageTypeSelect.innerHTML = '<option value="">Pilih Tipe Paket</option>';
            paymentPromoWrapper.classList.add('hidden');
            return;
        }

        // 1. Load promo dulu
        await loadPromos(categoryId);

        // 2. Load tipe paket
        await loadPackageTypes(categoryId);

        // Trigger change promo untuk filter tipe jika perlu
        paymentPromoSelect.dispatchEvent(new Event('change'));
    }

    // ─── Load Promo ─────────────────────────────────────────────
    async function loadPromos(categoryId) {
        showLoading(paymentPromoSelect, 'Memuat promo...');
        try {
            const res = await fetch(`/admin/packages/promos/category/${categoryId}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Gagal load promos');
            const data = await res.json();
            paymentPromoSelect.innerHTML = '<option value="">Tanpa Promo / Default</option>';
            data.forEach(p => {
                const label = `${p.name} (${p.months_paid} bln bayar + ${p.months_free} bln gratis)`;
                paymentPromoSelect.add(new Option(label, p.id));
            });
            paymentPromoWrapper.classList.toggle('hidden', data.length === 0);
            console.log('Promo loaded:', data.length, 'items');
        } catch (err) {
            console.error('Load promos error:', err);
            showError(paymentPromoSelect);
            paymentPromoWrapper.classList.add('hidden');
        }
    }

    // ─── Load Tipe Paket ────────────────────────────────────────
    async function loadPackageTypes(categoryId) {
        showLoading(packageTypeSelect);
        try {
            const res = await fetch(`/admin/packages/types/${categoryId}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Gagal load types');
            const data = await res.json();
            renderPackageTypes(data);
            console.log('Types loaded:', data.length, 'items');
        } catch (err) {
            console.error('Load types error:', err);
            showError(packageTypeSelect);
        }
    }

    function loadMasterBenefits() {
        fetch('/admin/benefits/list', { headers: { 'Accept': 'application/json' } })
            .then(res => res.ok ? res.json() : Promise.reject())
            .then(data => {
                allBenefits = data || [];
                console.log('Benefits loaded:', allBenefits.length, 'items');
            })
            .catch(err => console.error('Gagal load benefits:', err));
    }

    // ─── TV Toggle ──────────────────────────────────────────────
    document.getElementById('has_tv')?.addEventListener('change', function() {
        document.getElementById('tvSection').classList.toggle('hidden', !this.checked);
    });

    // ─── Toggle Streaming Section ──────────────────────────────
    async function toggleStreamingSection(typeId) {
        const section = document.getElementById('streamingAddonsSection');
        if (!typeId || !section) {
            section.classList.add('hidden');
            return;
        }

        try {
            const res = await fetch(`/admin/package-types/${typeId}`, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const typeData = await res.json();
            
            const supports = !!typeData.supports_streaming_addons;
            section.classList.toggle('hidden', !supports);
            
            console.log('Toggle streaming:', supports ? 'shown' : 'hidden');
        } catch (err) {
            console.error('Fetch type error:', err);
            section.classList.add('hidden');
        }
    }

    // ─── Benefit Repeater ────────────────────────────────
    function addBenefitRow(benefitId = '', durationValue = '12', durationUnit = 'BULAN') {
        benefitCounter++;
        let options = '<option value="">Pilih Benefit</option>';
        allBenefits.forEach(b => {
            const selected = String(b.id) === String(benefitId) ? 'selected' : '';
            options += `<option value="${b.id}" ${selected}>${b.name}${b.category ? ' (' + b.category + ')' : ''}</option>`;
        });

        const html = `
        <div class="benefit-row flex flex-wrap gap-4 items-end bg-base-100 p-4 rounded-xl border border-success/30 shadow-inner hover:shadow-md transition-all duration-300" data-index="${benefitCounter}">
            <div class="flex-1 min-w-[220px]">
                <label class="label">Benefit</label>
                <select name="benefits[${benefitCounter}][benefit_id]" class="select select-bordered w-full" required>
                    ${options}
                </select>
            </div>
            <div class="w-40">
                <label class="label">Gratis</label>
                <div class="flex">
                    <input type="number" name="benefits[${benefitCounter}][duration_value]" value="${durationValue}" class="input input-bordered w-16" min="0" required>
                    <select name="benefits[${benefitCounter}][duration_unit]" class="select select-bordered rounded-l-none">
                        <option value="BULAN" ${durationUnit==='BULAN'?'selected':''}>BULAN</option>
                        <option value="PEMBAYARAN" ${durationUnit==='PEMBAYARAN'?'selected':''}>PEMBAYARAN</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-error btn-sm mt-9 remove-benefit hover:scale-105 transition-transform">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>`;

        document.getElementById('benefitsRepeater').insertAdjacentHTML('beforeend', html);
    }

    document.getElementById('addBenefitBtn')?.addEventListener('click', () => addBenefitRow());

    // ─── Feature Repeater ────────────────────────────────────
    function addFeatureRow(label = '', icon = '', sortOrder = '') {
        featureCounter++;
        const html = `
        <div class="feature-row flex flex-wrap gap-4 items-end bg-base-100 p-4 rounded-xl border border-warning/30 shadow-inner hover:shadow-md transition-all duration-300" data-index="${featureCounter}">
            <div class="flex-1 min-w-[220px]">
                <label class="label">Deskripsi Fitur</label>
                <input type="text" name="features[${featureCounter}][label]" value="${label}" class="input input-bordered w-full" required placeholder="contoh: Internet UNLIMITED">
            </div>
            <div class="w-40">
                <label class="label">Icon (FontAwesome)</label>
                <input type="text" name="features[${featureCounter}][icon]" value="${icon}" class="input input-bordered w-full" placeholder="infinity / router / gift">
            </div>
            <div class="w-24">
                <label class="label">Urutan</label>
                <input type="number" name="features[${featureCounter}][sort_order]" value="${sortOrder}" class="input input-bordered w-full" min="0">
            </div>
            <button type="button" class="btn btn-error btn-sm mt-9 remove-feature hover:scale-105 transition-transform">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>`;

        document.getElementById('featuresRepeater').insertAdjacentHTML('beforeend', html);
    }

    document.getElementById('addFeatureBtn')?.addEventListener('click', () => addFeatureRow());

    // Hapus row
    document.addEventListener('click', e => {
        if (e.target.closest('.remove-benefit')) e.target.closest('.benefit-row').remove();
        if (e.target.closest('.remove-feature')) e.target.closest('.feature-row').remove();
    });

    // ─── Event Listeners ────────────────────────────────────────
    categorySelect.addEventListener('change', async function() {
        const catId = this.value;
        await loadPromoAndTypes(catId);
        paymentPromoSelect.value = '';
    });

    paymentPromoSelect.addEventListener('change', function() {
        const promoId = this.value;
        fetch(`/admin/packages/types/${categorySelect.value}`)
            .then(res => res.json())
            .then(data => {
                if (!promoId) {
                    renderPackageTypes(data);
                } else {
                    const filtered = data.filter(t => String(t.payment_promo_id) === promoId);
                    renderPackageTypes(filtered.length ? filtered : data);
                }
            });
    });

    // ─── Add Package ────────────────────────────────────────────
    window.addPackage = function() {
        form.reset();
        document.getElementById('method').value = 'POST';
        document.getElementById('packageId').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah Paket Baru';
        document.getElementById('imagePreview').classList.add('hidden');
        paymentPromoWrapper.classList.add('hidden');
        packageTypeSelect.innerHTML = '<option value="">Pilih Tipe Paket</option>';
        paymentPromoSelect.innerHTML = '<option value="">Tanpa Promo / Default</option>';

        document.getElementById('benefitsRepeater').innerHTML = '';
        document.getElementById('featuresRepeater').innerHTML = '';
        benefitCounter = featureCounter = 0;

        addBenefitRow('1', '12', 'BULAN');
        addBenefitRow('2', '12', 'BULAN');

        addFeatureRow('Internet UNLIMITED', 'infinity', '1');
        addFeatureRow('Include ONT/Modem', 'router', '2');
        addFeatureRow('Gratis Instalasi Rp500.000', 'gift', '3');

        document.getElementById('has_tv').checked = false;
        document.getElementById('tvSection').classList.add('hidden');

        modal.showModal();
    };

    // ─── Edit Package ───────────────────────────────────────────
    window.editPackage = function(id) {
        fetch(`/admin/packages/${id}`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('packageId').value = data.id;
                document.getElementById('method').value = 'PUT';
                document.getElementById('modalTitle').textContent = 'Edit Paket';

                // Set kategori → trigger load promo & tipe
                categorySelect.value = data.category_id;
                categorySelect.dispatchEvent(new Event('change'));

                // Tunggu load selesai, lalu set promo & tipe
                setTimeout(async () => {
                    await loadPromoAndTypes(data.category_id);

                    paymentPromoSelect.value = data.payment_promo_id || '';
                    paymentPromoSelect.dispatchEvent(new Event('change')); // trigger filter tipe

                    // Delay tambahan untuk pastikan tipe ter-render setelah filter promo
                    setTimeout(() => {
                        packageTypeSelect.value = data.package_type_id || '';
                        packageTypeSelect.dispatchEvent(new Event('change')); // trigger toggle streaming
                        toggleStreamingSection(data.package_type_id);

                        console.log('Final auto select:', {
                            promo: paymentPromoSelect.value,
                            type: packageTypeSelect.value,
                            streamingVisible: !document.getElementById('streamingAddonsSection').classList.contains('hidden')
                        });
                    }, 800);
                }, 1500);

                // Set field lain
                document.querySelector('[name="name"]').value = data.name || '';
                document.querySelector('[name="speed_mbps"]').value = data.speed_mbps || '';
                document.querySelector('[name="speed_up_to_mbps"]').value = data.speed_up_to_mbps || '';
                document.querySelector('[name="base_price"]').value = data.base_price || '';
                document.querySelector('[name="tax_included"]').checked = !!data.tax_included;
                document.querySelector('[name="promo_label"]').value = data.promo_label || '';
                document.querySelector('[name="is_active"]').checked = !!data.is_active;

                if (data.image) {
                    document.getElementById('previewImg').src = '{{ asset("storage") }}/' + data.image;
                    document.getElementById('imagePreview').classList.remove('hidden');
                } else {
                    document.getElementById('imagePreview').classList.add('hidden');
                }

                document.getElementById('has_tv').checked = !!data.has_tv;
                document.getElementById('tvSection').classList.toggle('hidden', !data.has_tv);
                if (data.has_tv) {
                    document.querySelector('[name="channel_count"]').value = data.channel_count || 76;
                    document.querySelector('[name="stb_info"]').value = data.stb_info || 'Termasuk STB Android 12';
                }

                document.getElementById('benefitsRepeater').innerHTML = '';
                benefitCounter = 0;
                if (data.benefits && data.benefits.length) {
                    data.benefits.forEach(b => {
                        addBenefitRow(b.pivot.benefit_id, b.pivot.duration_value || '12', b.pivot.duration_unit || 'BULAN');
                    });
                } else {
                    addBenefitRow('1', '12', 'BULAN');
                    addBenefitRow('2', '12', 'BULAN');
                }

                document.getElementById('featuresRepeater').innerHTML = '';
                featureCounter = 0;
                if (data.features && data.features.length) {
                    data.features.forEach(f => {
                        addFeatureRow(f.label, f.icon || '', f.sort_order || '');
                    });
                } else {
                    addFeatureRow('Internet UNLIMITED', 'infinity', '1');
                    addFeatureRow('Include ONT/Modem', 'router', '2');
                    addFeatureRow('Gratis Instalasi Rp500.000', 'gift', '3');
                }

                // Streaming Add-ons – auto centang
                if (data.streaming_addons && Array.isArray(data.streaming_addons)) {
                    document.querySelectorAll('input[name="streaming_addons[]"]').forEach(checkbox => {
                        const addonId = checkbox.value;
                        const isChecked = data.streaming_addons.some(addon =>
                            String(addon.id) === String(addonId) ||
                            String(addon.streaming_addon_id) === String(addonId)
                        );
                        checkbox.checked = isChecked;
                    });
                }

                modal.showModal();
            })
            .catch(err => {
                console.error('Edit error:', err);
                Swal.fire('Error', 'Gagal memuat data paket', 'error');
            });
    };

    // ─── Package Type Change ────────────────────────────────────
    packageTypeSelect.addEventListener('change', function() {
        toggleStreamingSection(this.value);
    });

    // ─── Client-Side Validation ────────────────────────────────
    form.addEventListener('submit', function(e) {
        let valid = true;

        ['category_id', 'package_type_id', 'name', 'speed_mbps', 'base_price'].forEach(field => {
            const el = document.querySelector(`[name="${field}"]`);
            if (!el.value) {
                valid = false;
                el.classList.add('border-red-500', 'focus:border-red-500');
                Swal.fire('Error', `Field ${field.replace('_', ' ')} wajib diisi!`, 'error');
            } else {
                el.classList.remove('border-red-500');
            }
        });

        if (document.querySelectorAll('.benefit-row').length < 1) {
            valid = false;
            Swal.fire('Error', 'Harap tambah minimal 1 benefit!', 'error');
        }
        if (document.querySelectorAll('.feature-row').length < 1) {
            valid = false;
            Swal.fire('Error', 'Harap tambah minimal 1 fitur standar!', 'error');
        }

        if (!valid) e.preventDefault();
    });

    window.deletePackage = function(id) {
        Swal.fire({
            title: 'Yakin hapus paket?',
            text: "Data akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/packages/${id}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Paket dihapus', 'success');
                    },
                    error: () => Swal.fire('Gagal', 'Terjadi kesalahan', 'error')
                });
            }
        });
    };

    // Image Preview
    document.querySelector('[name="image"]')?.addEventListener('change', e => {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('previewImg').src = URL.createObjectURL(file);
            document.getElementById('imagePreview').classList.remove('hidden');
        }
    });

    // Form Submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = document.getElementById('method').value;
        let url = '/admin/packages';
        if (method === 'PUT') {
            url += '/' + document.getElementById('packageId').value;
            formData.append('_method', 'PUT');
        }

        const btn = this.querySelector('button[type="submit"]');
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="loading loading-spinner"></span> Menyimpan...';

        $.ajax({
            url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: res => {
                modal.close();
                table.ajax.reload();
                Swal.fire('Sukses', 'Paket berhasil disimpan', 'success');
            },
            error: xhr => {
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
            },
            complete: () => {
                btn.disabled = false;
                btn.innerHTML = original;
            }
        });
    });

    // DataTable
    $(document).ready(function() {
        table = $('#packageTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.packages.data") }}',
            responsive: true,
            language: {
                processing: '<div class="loading loading-spinner loading-lg text-purple-600"></div>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_",
                info: "_START_ sampai _END_ dari _TOTAL_",
                paginate: { previous: "<", next: ">" }
            },
            columnDefs: [
                { orderable: false, targets: [0, 8] },
                { className: "text-center", targets: [0, 5, 7, 8] }
            ],
            columns: [
                { data: null, render: (d, t, r, m) => m.row + m.settings._iDisplayStart + 1 },
                { data: 'category' },
                { data: 'type' },
                { data: 'promo' },
                { data: 'name', render: d => `<div class="font-medium">${d}</div>` },
                { data: 'speed', render: d => `<span class="font-bold">${d}</span>` },
                { data: 'price_rp' },
                { data: 'is_active', render: d => d ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-error">Nonaktif</span>' },
                {
                    data: null,
                    render: (d, t, r) => `
                        <div class="flex justify-center gap-2">
                            <button onclick="editPackage(${r.id})" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button onclick="deletePackage(${r.id})" class="btn btn-sm btn-error"><i class="fa-solid fa-trash-can"></i></button>
                        </div>`
                }
            ],
            pageLength: 10
        });

        // Load master benefits sekali
        loadMasterBenefits();
    });
</script>
@endpush