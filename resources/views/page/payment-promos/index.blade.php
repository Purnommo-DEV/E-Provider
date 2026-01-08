@extends('layouts.app')

@section('title', 'Manajemen Promo Pembayaran')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl md:text-4xl font-bold">Manajemen Promo Pembayaran</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                    Kelola promo pembayaran di muka seperti "12 Get 6", "9 Get 3", dll.<br>
                    Promo ini hanya akan muncul pada kategori yang memiliki flag "Punya Promo Pembayaran" (saat ini Residensial).
                </p>
            </div>
            <button onclick="addPromo()"
                    class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                + Tambah Promo
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 md:p-8">
        <div class="overflow-x-auto">
            <table id="promoTable" class="table table-zebra w-full text-sm">
                <thead class="bg-purple-100 text-purple-900">
                    <tr>
                        <th class="text-left py-3 px-4">No</th>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-left py-3 px-4">Nama Promo</th>
                        <th class="text-left py-3 px-4">Detail Promo</th>
                        <th class="text-center py-3 px-4">Status</th>
                        <th class="text-center py-3 px-4 w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<dialog id="promoModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-3xl bg-white">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-2xl md:text-3xl font-bold text-purple-900" id="modalTitle">Tambah Promo Pembayaran</h3>
            <button class="btn btn-ghost btn-circle btn-lg" onclick="promoModal.close()">
                ✕
            </button>
        </div>

        <form id="promoForm" class="space-y-6">
            @csrf
            <input type="hidden" id="promoId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Kategori</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" class="select select-bordered w-full select-lg" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Nama Promo</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full input-lg"
                           placeholder="e.g., 12 Get 6" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Bayar (bulan)</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="months_paid" class="input input-bordered w-full input-lg" min="1" required>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Gratis (bulan)</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="months_free" class="input input-bordered w-full input-lg" min="0" value="0" required>
                </div>
            </div>

            <div class="form-control mt-8">
                <label class="label cursor-pointer justify-start gap-4">
                    <input type="checkbox" name="is_active" class="checkbox checkbox-primary checkbox-lg" checked>
                    <span class="label-text font-semibold text-gray-700">Aktif (tampil di tab promo pembayaran)</span>
                </label>
                <p class="text-xs text-gray-500 ml-12 mt-1">Nonaktifkan jika promo sementara tidak ditampilkan.</p>
            </div>

            <div class="modal-action mt-10 flex justify-end gap-4">
                <button type="button" class="btn btn-ghost btn-lg" onclick="promoModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-lg bg-purple-700 hover:bg-purple-800">
                    Simpan Promo
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('promoModal');
    const form = document.getElementById('promoForm');
    let table;

    $(document).ready(function() {
        table = $('#promoTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.payment-promos.data") }}',
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                processing: "Memuat data promo...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                paginate: { previous: "‹", next: "›" },
                emptyTable: "Belum ada promo pembayaran"
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center' },
                { data: 'category_name', className: 'font-medium' },
                { data: 'name', className: 'font-medium' },
                { data: 'detail' },
                { data: 'status', className: 'text-center' },
                { data: 'action', orderable: false, className: 'text-center whitespace-nowrap' }
            ],
            responsive: true
        });
    });

    window.addPromo = function() {
        form.reset();
        $('#method').val('POST');
        $('#promoId').val('');
        $('#modalTitle').text('Tambah Promo Pembayaran');
        modal.showModal();
    };

    window.editPromo = function(id) {
        $.get('/admin/payment-promos/' + id, function(data) {
            $('[name=category_id]').val(data.category_id);
            $('[name=name]').val(data.name);
            $('[name=months_paid]').val(data.months_paid);
            $('[name=months_free]').val(data.months_free);
            $('[name=is_active]').prop('checked', data.is_active);
            $('#method').val('PUT');
            $('#promoId').val(id);
            $('#modalTitle').text('Edit Promo Pembayaran');
            modal.showModal();
        });
    };

    window.deletePromo = function(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus promo ini?',
            text: "Promo akan hilang dari tab pembayaran di landing page.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/payment-promos/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', 'Promo berhasil dihapus.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', 'Tidak dapat menghapus promo', 'error');
                    }
                });
            }
        });
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#promoId').val();
        let url = '/admin/payment-promos';

        if (method === 'PUT') {
            url = '/admin/payment-promos/' + id;
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                modal.close();
                table.ajax.reload();
                Swal.fire('Sukses!', res.success, 'success');
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan promo';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endpush