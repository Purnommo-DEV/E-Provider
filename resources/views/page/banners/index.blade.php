@extends('layouts.app')

@section('title', 'Kelola Banner & Promo')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h3 class="text-2xl md:text-3xl font-bold">Kelola Banner Carousel</h3>
            <button onclick="addBanner()" class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Tambah Banner
            </button>
        </div>
    </div>

    <div class="p-6">
        <div class="card bg-base-100 shadow-md border border-gray-200">
            <div class="card-body p-0">
                <table id="bannerTable" class="table table-lg w-full">
                    <thead class="bg-purple-50 text-purple-900">
                        <tr>
                            <th class="w-12">No</th>
                            <th>Preview Gambar</th>
                            <th class="w-32">Urutan</th>
                            <th class="w-32">Status</th>
                            <th class="text-center w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form Sederhana -->
<dialog id="bannerModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white rounded-2xl shadow-2xl">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-3xl font-extrabold text-purple-900" id="modalTitle">Tambah Banner</h3>
            <button class="btn btn-ghost btn-circle text-2xl hover:bg-gray-200" onclick="bannerModal.close()">✕</button>
        </div>

        <form id="bannerForm" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <input type="hidden" id="bannerId">
            <input type="hidden" id="method" value="POST">

            <!-- Upload Gambar -->
            <div class="space-y-3">
                <label class="label font-semibold text-gray-700">Gambar Banner <span class="text-red-500">*</span></label>
                <p class="text-sm text-gray-500">Rekomendasi ukuran: <strong>1920 × 500 px</strong> (rasio 2.4:1)</p>
                
                <input 
                    type="file" 
                    name="image" 
                    class="file-input file-input-bordered file-input-primary w-full h-14 rounded-xl shadow-sm hover:shadow-md transition-all" 
                    accept="image/*" 
                    required>

                <!-- Preview Gambar -->
                <div id="imagePreview" class="mt-6 hidden">
                    <p class="text-sm font-medium text-gray-600 mb-3">Pratinjau Gambar:</p>
                    <div class="relative aspect-[1920/500] w-full rounded-2xl overflow-hidden shadow-xl border-4 border-purple-100 bg-black">
                        <img id="previewImg"
                             class="w-full h-full object-contain"
                             src=""
                             alt="Preview Banner">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent pointer-events-none"></div>
                    </div>
                </div>

            </div>

            <!-- Urutan Tampil -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="label font-semibold text-gray-700">Urutan Tampil</label>
                    <input 
                        type="number" 
                        name="order" 
                        value="0"
                        min="0"
                        class="input input-bordered w-full h-12 rounded-xl focus:ring-4 focus:ring-purple-300 focus:border-purple-600 transition-all shadow-sm"
                        placeholder="0 = paling atas">
                    <p class="text-xs text-gray-500 mt-1">Angka kecil akan muncul lebih dulu di carousel</p>
                </div>

                <div class="flex items-center justify-center md:justify-end">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm flex items-center gap-6">
                        <div>
                            <p class="font-semibold text-gray-800">Tampilkan Banner</p>
                            <p class="text-sm text-gray-600">Aktifkan untuk ditampilkan di halaman utama</p>
                        </div>
                        <div class="form-control">
                            <!-- Hidden input untuk memastikan nilai selalu dikirim -->
                            <input type="hidden" name="is_active" value="0">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                class="checkbox checkbox-primary checkbox-lg rounded-lg"
                                checked>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="modal-action flex justify-end gap-4 pt-6 border-t">
                <button type="button" class="btn btn-ghost btn-lg rounded-xl px-8 shadow-md hover:shadow-lg" onclick="bannerModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-700 to-purple-800 hover:from-purple-800 hover:to-purple-900 text-white btn-lg rounded-xl px-10 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <i class="fas fa-save mr-3"></i>
                    Simpan Banner
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>Tutup</button>
    </form>
</dialog>
@endsection

@push('styles')
<!-- Font Awesome CDN - WAJIB untuk icon fas fa-edit, fa-trash, dll -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>

<script>
    const modal = document.getElementById('bannerModal');
    const form = document.getElementById('bannerForm');
    let table;

    $(document).ready(function() {
        table = $('#bannerTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.banners.data") }}',
            language: {
                processing: '<div class="loading loading-spinner loading-lg text-purple-600"></div>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { previous: "<", next: ">" }
            },
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [1, 4] },
                { className: "text-center", targets: [0, 2, 3, 4] }
            ],
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { 
                    data: 'image_preview', 
                    orderable: false,
                    className: "text-center"
                },
                { data: 'order', render: data => `<span class="font-bold text-lg">${data}</span>` },
                {
                    data: 'is_active',
                    render: data => data
                        ? '<span class="badge badge-success badge-lg">Aktif</span>'
                        : '<span class="badge badge-error badge-lg">Nonaktif</span>'
                },
                {
                    data: 'action',
                    render: (data, type, row) => `
                        <div class="flex justify-center gap-2">
                            <button onclick="editBanner(${row.id})" class="btn btn-sm btn-warning text-white">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteBanner(${row.id})" class="btn btn-sm btn-error">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `
                }
            ],
            order: [['2', 'asc']],
            pageLength: 10
        });
    });

    window.addBanner = function() {
        form.reset();
        $('#method').val('POST');
        $('#bannerId').val('');
        $('#modalTitle').text('Tambah Banner');
        $('#imagePreview').addClass('hidden');
        $('[name="image"]').prop('required', true);
        $('[name="order"]').val(0);
        $('[name="is_active"]').prop('checked', true);
        modal.showModal();
    }

    window.editBanner = function(id) {
        $.get('/admin/banners/' + id, function(data) {
            $('#method').val('PUT');
            $('#bannerId').val(id);
            $('#modalTitle').text('Edit Banner');
            $('[name="order"]').val(data.order);
            $('[name="is_active"]').prop('checked', data.is_active);
            $('#previewImg').attr('src', '{{ asset("storage") }}/' + data.image);
            $('#imagePreview').removeClass('hidden');
            $('[name="image"]').prop('required', false);
            modal.showModal();
        });
    }

    window.deleteBanner = function(id) {
        Swal.fire({
            title: 'Yakin hapus banner ini?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/banners/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Banner berhasil dihapus.', 'success');
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    }
                });
            }
        });
    }

    $('[name="image"]').change(function(e) {
        if (e.target.files && e.target.files[0]) {
            $('#previewImg').attr('src', URL.createObjectURL(e.target.files[0]));
            $('#imagePreview').removeClass('hidden');
        }
    });

    // PERBAIKAN UTAMA: Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#bannerId').val();
        let url = '/admin/banners';

        if (method === 'PUT') {
            url = '/admin/banners/' + id;
            formData.append('_method', 'PUT');
        }

        // Tambahkan loading state (opsional, lebih smooth)
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
                modal.close(); // Tutup modal dulu
                table.ajax.reload();
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: res.success || 'Banner berhasil disimpan',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
                modal.close(); // Pastikan modal ditutup sebelum Swal error
                const message = xhr.responseJSON?.message || 
                                xhr.responseJSON?.error || 
                                'Gagal menyimpan banner. Silakan coba lagi.';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                });
            },
            complete: function() {
                // Kembalikan tombol ke semula
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endpush