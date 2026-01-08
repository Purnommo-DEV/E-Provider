@extends('layouts.app')

@section('title', 'Kelola Channel & Platform Streaming')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl md:text-4xl font-bold">Kelola Channel & Platform Streaming</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                    Tambah/edit logo channel TV atau platform streaming seperti Vidio, Vision+, WeTV, Bstation, dll.<br>
                    Logo akan muncul di grid section "Hiburan Terlengkap" pada landing page.
                </p>
            </div>
            <button onclick="addChannel()"
                    class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Tambah Channel
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 md:p-8">
        <div class="overflow-x-auto">
            <table id="channelTable" class="table table-zebra w-full text-sm">
                <thead class="bg-purple-100 text-purple-900">
                    <tr>
                        <th class="text-left py-3 px-4">No</th>
                        <th class="text-left py-3 px-4">Nama Channel</th>
                        <th class="text-center py-3 px-4">Preview Logo</th>
                        <th class="text-center py-3 px-4">Tipe</th>
                        <th class="text-center py-3 px-4">Urutan</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form Channel -->
<dialog id="channelModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white rounded-2xl shadow-2xl">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-3xl font-extrabold text-purple-900" id="modalTitle">Tambah Channel</h3>
            <button class="btn btn-ghost btn-circle text-2xl hover:bg-gray-200" onclick="channelModal.close()">✕</button>
        </div>

        <form id="channelForm" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <input type="hidden" id="channelId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="label font-semibold text-gray-700">Nama Channel <span class="text-red-500">*</span></label>
                    <input type="text" name="name" class="input input-bordered w-full input-lg rounded-xl shadow-sm" placeholder="e.g., Vidio" required>
                </div>
                <div>
                    <label class="label font-semibold text-gray-700">Tipe</label>
                    <select name="type" class="select select-bordered w-full select-lg rounded-xl shadow-sm">
                        <option value="streaming">Streaming Platform</option>
                        <option value="tv">TV Channel</option>
                    </select>
                </div>
            </div>

            <div class="space-y-3">
                <label class="label font-semibold text-gray-700">Logo Channel <span class="text-red-500">*</span></label>
                <p class="text-sm text-gray-500">Rekomendasi ukuran: 200x100 px (logo horizontal), format PNG/WEBP</p>
                <input type="file" name="logo" class="file-input file-input-bordered file-input-primary w-full h-14 rounded-xl shadow-sm hover:shadow-md transition-all" accept="image/*">
                <div id="logoPreview" class="mt-6 hidden">
                    <p class="text-sm font-medium text-gray-600 mb-3">Pratinjau Logo:</p>
                    <img id="previewLogo" class="w-48 h-auto object-contain rounded-2xl shadow-xl border-4 border-purple-100" src="" alt="Preview Logo">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="label font-semibold text-gray-700">Urutan Tampil</label>
                    <input type="number" name="order" value="0" min="0" class="input input-bordered w-full input-lg rounded-xl shadow-sm">
                    <p class="text-sm text-gray-500 mt-1">Angka kecil akan muncul lebih dulu di grid</p>
                </div>
                <div class="flex items-center">
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm flex items-center gap-6">
                        <div>
                            <p class="font-semibold text-gray-800">Aktifkan Channel</p>
                            <p class="text-sm text-gray-600">Tampilkan di landing page</p>
                        </div>
                        <div class="form-control">
                            <input type="checkbox" name="is_active" class="checkbox checkbox-primary checkbox-lg" checked>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-action flex justify-end gap-4 pt-6 border-t">
                <button type="button" class="btn btn-ghost btn-lg rounded-xl px-8 shadow-md hover:shadow-lg" onclick="channelModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-700 to-purple-800 hover:from-purple-800 hover:to-purple-900 text-white btn-lg rounded-xl px-10 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <i class="fas fa-save mr-3"></i>
                    Simpan Channel
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.min.js"></script>
<script>
    // Deklarasikan di scope global agar bisa diakses dari window.addChannel dll
    const modal = document.getElementById('channelModal');
    const form = document.getElementById('channelForm');
    let table;

    $(document).ready(function() {
        table = $('#channelTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.channels.data") }}',
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                processing: '<div class="loading loading-spinner loading-lg text-purple-600"></div>',
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                paginate: { previous: "‹", next: "›" },
                emptyTable: "Belum ada channel/platform"
            },
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [2, 6] },
                { className: "text-center", targets: [0, 2, 3, 4, 5, 6] }
            ],
            columns: [
                { data: null, render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1 },
                { data: 'name', className: "font-medium" },
                { data: 'logo_preview', orderable: false },
                { data: 'type', render: data => data === 'streaming' ? 'Streaming' : 'TV Channel' },
                { data: 'order', render: data => `<span class="font-bold text-lg">${data}</span>` },
                { data: 'status' },
                { data: 'action', orderable: false }
            ],
            order: [['4', 'asc']],
            pageLength: 10
        });
    });

    window.addChannel = function() {
        form.reset();
        $('#method').val('POST');
        $('#channelId').val('');
        $('#modalTitle').text('Tambah Channel Baru');
        $('#logoPreview').addClass('hidden');
        $('[name="logo"]').prop('required', true);
        $('[name="order"]').val(table.rows().count() + 1); // urutan otomatis terakhir
        modal.showModal();
    };

    window.editChannel = function(id) {
        $.get('/admin/channels/' + id, function(data) {
            $('#method').val('PUT');
            $('#channelId').val(id);
            $('#modalTitle').text('Edit Channel');
            $('[name="name"]').val(data.name);
            $('[name="type"]').val(data.type);
            $('[name="order"]').val(data.order);
            $('[name="is_active"]').prop('checked', data.is_active);
            if (data.logo) {
                $('#previewLogo').attr('src', '{{ asset("storage") }}/' + data.logo);
                $('#logoPreview').removeClass('hidden');
                $('[name="logo"]').prop('required', false);
            } else {
                $('#logoPreview').addClass('hidden');
                $('[name="logo"]').prop('required', true);
            }
            modal.showModal();
        });
    };

    window.deleteChannel = function(id) {
        Swal.fire({
            title: 'Yakin hapus channel ini?',
            text: "Logo dan data akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/channels/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Channel berhasil dihapus.', 'success');
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus.', 'error');
                    }
                });
            }
        });
    };

    // Preview logo baru
    $('[name="logo"]').change(function(e) {
        if (e.target.files && e.target.files[0]) {
            $('#previewLogo').attr('src', URL.createObjectURL(e.target.files[0]));
            $('#logoPreview').removeClass('hidden');
        }
    });

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#channelId').val();
        let url = '/admin/channels';
        if (method === 'PUT') {
            url = '/admin/channels/' + id;
            formData.append('_method', 'PUT');
        }

        const submitBtn = this.querySelector('button[type="submit"]');
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
                Swal.fire('Sukses!', res.success || 'Channel berhasil disimpan', 'success');
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Gagal menyimpan channel';
                Swal.fire('Error', message, 'error');
            },
            complete: function() {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });
    });
</script>
@endpush