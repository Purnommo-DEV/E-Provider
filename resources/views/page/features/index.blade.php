@extends('layouts.app')

@section('title', 'Kelola Keunggulan')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-2xl md:text-3xl font-bold">Kelola Keunggulan</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base">
                    Tampilkan maksimal 6 item untuk tampilan optimal di landing page.<br>
                    Pilih icon dari dropdown di bawah (Heroicons Solid).
                </p>
            </div>
            <button onclick="addFeature()" class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Keunggulan
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <div class="card-body p-0">
                <table id="featureTable" class="table table-lg w-full">
                    <thead class="bg-purple-50 text-purple-900">
                        <tr>
                            <th class="w-12">No</th>
                            <th class="w-32">Icon</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th class="w-32">Status</th>
                            <th class="w-24">Urutan</th>
                            <th class="text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<dialog id="featureModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white rounded-2xl shadow-2xl">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-3xl font-extrabold text-purple-900" id="modalTitle">Tambah Keunggulan</h3>
            <button class="btn btn-ghost btn-circle text-2xl hover:bg-gray-200" onclick="featureModal.close()">✕</button>
        </div>

        <form id="featureForm" class="space-y-8">
            @csrf
            <input type="hidden" id="featureId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="label font-semibold text-gray-700">Judul Keunggulan <span class="text-red-500">*</span></label>
                    <input type="text" name="title" placeholder="Contoh: Layanan 24 Jam" class="input input-bordered w-full h-12 rounded-xl shadow-sm focus:ring-4 focus:ring-purple-300" required>
                </div>

                <div class="space-y-2">
                    <label class="label font-semibold text-gray-700">Pilih Icon Heroicons <span class="text-red-500">*</span></label>
                    
                    <input type="hidden" name="icon" id="selectedIcon" required>

                    <button type="button" id="iconDropdownBtn" class="btn btn-outline w-full justify-between rounded-xl shadow-sm hover:shadow-md text-left">
                        <span id="selectedIconPreview" class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-icons text-gray-400"></i>
                            </div>
                            <span id="selectedIconName">Pilih icon...</span>
                        </span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>

                    <div id="iconDropdownList" class="hidden absolute z-50 mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-200 max-h-96 overflow-y-auto">
                        @php
                            $popularIcons = [
                                'academic-cap', 'book-open', 'graduation-cap', 'light-bulb', 'sparkles', 'trophy', 'star', 'check-badge', 'certificate', 'medal',
                                'chat-bubble-left-right', 'chat-bubble-oval-left', 'megaphone', 'envelope', 'phone', 'device-phone-mobile', 'inbox', 'paper-airplane',
                                'bolt', 'rocket-launch', 'wifi', 'cpu-chip', 'server', 'cloud', 'globe-alt', 'arrow-path', 'arrows-pointing-out', 'command-line',
                                'shield-check', 'shield-exclamation', 'lock-closed', 'lock-open', 'key', 'finger-print', 'eye', 'eye-slash',
                                'clock', 'calendar', 'calendar-days', 'hourglass',
                                'currency-dollar', 'banknotes', 'credit-card', 'wallet', 'briefcase', 'chart-bar', 'chart-pie', 'trending-up', 'trending-down',
                                'users', 'user-group', 'user-plus', 'user', 'face-smile', 'hand-thumb-up', 'heart',
                                'map', 'map-pin', 'truck', 'building-office', 'home', 'building-storefront', 'flag',
                                'lifebuoy', 'wrench-screwdriver', 'cog-6-tooth', 'question-mark-circle', 'information-circle', 'chat-bubble-left-ellipsis',
                                'photo', 'camera', 'film', 'document-text', 'newspaper', 'play-circle', 'musical-note'
                            ];
                        @endphp

                        @foreach($popularIcons as $icon)
                            <button type="button"
                                    data-icon="{{ $icon }}"
                                    class="w-full flex items-center gap-4 px-4 py-3 hover:bg-purple-50 transition">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center shadow icon-container">
                                    <div class="w-7 h-7 animate-pulse bg-purple-200 rounded"></div>
                                </div>
                                <span class="text-gray-700">{{ str_replace('-', ' ', ucwords($icon)) }}</span>
                            </button>
                        @endforeach
                    </div>

                    <p class="text-xs text-gray-500 mt-2">
                        Pilih icon dari list di atas. Untuk icon lain, kunjungi <a href="https://heroicons.com" target="_blank" class="link link-primary">heroicons.com</a>.
                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="label font-semibold text-gray-700">Deskripsi <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" placeholder="Jelaskan keunggulan ini secara singkat dan menarik..." class="textarea textarea-bordered w-full rounded-xl shadow-sm focus:ring-4 focus:ring-purple-300" required></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="label font-semibold text-gray-700">Urutan Tampil</label>
                    <input type="number" name="order" value="0" min="0" class="input input-bordered w-full h-12 rounded-xl shadow-sm" placeholder="0 = paling atas">
                    <p class="text-xs text-gray-500 mt-1">Angka kecil muncul lebih dulu</p>
                </div>

                <div class="flex items-center justify-center md:justify-end">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm flex items-center gap-6">
                        <div>
                            <p class="font-semibold text-gray-800">Tampilkan di Landing Page</p>
                            <p class="text-sm text-gray-600">Aktifkan agar muncul di halaman utama</p>
                        </div>
                        <div class="form-control">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary checkbox-lg rounded-lg" checked>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action flex justify-end gap-4 pt-6 border-t">
                <button type="button" class="btn btn-ghost btn-lg rounded-xl px-8 shadow-md hover:shadow-lg" onclick="featureModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-700 to-purple-800 hover:from-purple-800 hover:to-purple-900 text-white btn-lg rounded-xl px-10 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <i class="fa-solid fa-save mr-3"></i> Simpan Keunggulan
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
    const modal = document.getElementById('featureModal');
    const form = document.getElementById('featureForm');
    let table;

    // Daftar icon populer (aman dari htmlspecialchars)
    const popularIcons = @json($popularIcons);

    // Fungsi load SVG Heroicons inline (bersih total)
    async function loadHeroicon(iconName, targetElement) {
        if (!iconName) {
            targetElement.innerHTML = '<span class="text-gray-400 text-xs">Tidak ada</span>';
            return;
        }

        const url = `https://raw.githubusercontent.com/tailwindlabs/heroicons/master/optimized/24/solid/${iconName}.svg?sanitize=true`;

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Icon not found');

            let svgText = await response.text();

            // Bersihkan semua elemen tidak perlu
            svgText = svgText
                .replace(/<title>.*<\/title>/gi, '')
                .replace(/<desc>.*<\/desc>/gi, '')
                .replace(/>\s+</g, '><')
                .trim();

            // Tambahkan class styling
            svgText = svgText.replace('<svg', '<svg class="w-full h-full text-purple-700" fill="currentColor"');

            targetElement.innerHTML = svgText;
        } catch (error) {
            targetElement.innerHTML = '<i class="fa-solid fa-exclamation-triangle text-red-500"></i>';
        }
    }

    $(document).ready(function() {
        table = $('#featureTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.features.data") }}',
            responsive: true,
            language: {
                processing: '<div class="loading loading-spinner loading-lg text-purple-600"></div>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { previous: "<", next: ">" }
            },
            columnDefs: [
                { orderable: false, targets: [1, 6] },
                { className: "text-center", targets: [0, 1, 4, 5, 6] }
            ],
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                {
                    data: 'icon',
                    render: function(icon, type, row) {
                        if (type === 'display') {
                            if (!icon) {
                                return '<div class="w-16 h-16 mx-auto bg-gray-100 rounded-xl flex items-center justify-center"><span class="text-gray-400 text-xs">Tidak ada</span></div>';
                            }
                            return `<div class="w-16 h-16 mx-auto bg-purple-100 rounded-xl flex items-center justify-center shadow-md icon-cell" data-icon="${icon}"><div class="w-10 h-10 animate-pulse bg-purple-200 rounded"></div></div>`;
                        }
                        return icon || '';
                    }
                },
                { data: 'title', render: data => `<div class="font-medium">${data}</div>` },
                { data: 'description', render: data => data.length > 80 ? data.substr(0, 80) + '...' : data },
                {
                    data: 'is_active',
                    render: data => data ? '<span class="badge badge-success badge-lg">Aktif</span>' : '<span class="badge badge-error badge-lg">Nonaktif</span>'
                },
                { data: 'order', render: data => `<span class="font-bold text-lg">${data}</span>` },
                {
                    data: null,
                    render: (data, type, row) => `
                        <div class="flex justify-center gap-2">
                            <button onclick="editFeature(${row.id})" class="btn btn-sm btn-warning text-white" title="Edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteFeature(${row.id})" class="btn btn-sm btn-error" title="Hapus">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    `
                }
            ],
            order: [[5, 'asc']],
            pageLength: 10
        });

        // Load icon setelah tabel digambar
        table.on('draw', loadIconsInTable);
        table.on('init', loadIconsInTable);
    });

    function loadIconsInTable() {
        document.querySelectorAll('.icon-cell[data-icon]').forEach(cell => {
            const icon = cell.getAttribute('data-icon');
            loadHeroicon(icon, cell);
        });
    }

    // Custom Icon Dropdown
    const iconDropdownBtn = document.getElementById('iconDropdownBtn');
    const iconDropdownList = document.getElementById('iconDropdownList');
    const selectedIconPreview = document.getElementById('selectedIconPreview');
    const selectedIconName = document.getElementById('selectedIconName');
    const selectedIconInput = document.getElementById('selectedIcon');

    iconDropdownBtn.addEventListener('click', () => {
        iconDropdownList.classList.toggle('hidden');
    });

    iconDropdownList.querySelectorAll('button').forEach((btn, index) => {
        btn.addEventListener('click', () => {
            const iconName = btn.dataset.icon;
            const niceName = btn.querySelector('span').textContent;

            // Update preview
            selectedIconPreview.innerHTML = `
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center icon-preview-container">
                    <div class="w-6 h-6"></div>
                </div>
                <span>${niceName}</span>
            `;
            selectedIconName.textContent = niceName;
            selectedIconInput.value = iconName;

            // Load icon ke preview
            const container = selectedIconPreview.querySelector('.icon-preview-container > div');
            loadHeroicon(iconName, container);

            iconDropdownList.classList.add('hidden');
        });

        // Load icon ke dropdown saat pertama dibuka
        const container = btn.querySelector('.icon-container');
        loadHeroicon(popularIcons[index], container);
    });

    document.addEventListener('click', (e) => {
        if (!iconDropdownBtn.contains(e.target) && !iconDropdownList.contains(e.target)) {
            iconDropdownList.classList.add('hidden');
        }
    });

    window.addFeature = function() {
        form.reset();
        $('#method').val('POST');
        $('#featureId').val('');
        $('#modalTitle').text('Tambah Keunggulan');

        // Reset icon selector
        selectedIconPreview.innerHTML = `
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-icons text-gray-400"></i>
            </div>
            <span>Pilih icon...</span>
        `;
        selectedIconName.textContent = 'Pilih icon...';
        selectedIconInput.value = '';

        $('[name="is_active"][type="hidden"]').val('0');
        $('[name="is_active"][type="checkbox"]').prop('checked', true);
        $('[name="order"]').val(0);

        modal.showModal();
    }

    window.editFeature = function(id) {
        $.get('/admin/features/' + id, function(data) {
            $('#method').val('PUT');
            $('#featureId').val(id);
            $('#modalTitle').text('Edit Keunggulan');
            $('[name="title"]').val(data.title);
            $('[name="description"]').val(data.description);
            $('[name="order"]').val(data.order);
            $('[name="is_active"][type="hidden"]').val('0');
            $('[name="is_active"][type="checkbox"]').prop('checked', data.is_active);

            if (data.icon) {
                const niceName = data.icon.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
                selectedIconPreview.innerHTML = `
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center icon-preview-container">
                        <div class="w-6 h-6"></div>
                    </div>
                    <span>${niceName}</span>
                `;
                selectedIconName.textContent = niceName;
                selectedIconInput.value = data.icon;

                const container = selectedIconPreview.querySelector('.icon-preview-container > div');
                loadHeroicon(data.icon, container);
            } else {
                selectedIconPreview.innerHTML = `
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-icons text-gray-400"></i>
                    </div>
                    <span>Pilih icon...</span>
                `;
                selectedIconInput.value = '';
            }

            modal.showModal();
        });
    }

    window.deleteFeature = function(id) {
        Swal.fire({
            title: 'Yakin hapus keunggulan ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/features/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Keunggulan berhasil dihapus.', 'success');
                    }
                });
            }
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#featureId').val();
        let url = '/admin/features';
        if (method === 'PUT') {
            url = '/admin/features/' + id;
            formData.append('_method', 'PUT');
        }

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="loading loading-spinner"></span> Menyimpan...';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                modal.close();
                table.ajax.reload();
                Swal.fire('Sukses!', res.success || 'Keunggulan berhasil disimpan', 'success');
            },
            error: function(xhr) {
                modal.close();
                Swal.fire('Gagal', xhr.responseJSON?.message || 'Gagal menyimpan keunggulan', 'error');
            },
            complete: function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endpush