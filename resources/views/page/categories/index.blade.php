@extends('layouts.app')

@section('title', 'Manajemen Kategori Utama')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl md:text-4xl font-bold">Manajemen Kategori Utama</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                    Kelola kategori paket internet (Residensial & Bisnis SME).<br>
                    Kategori dengan promo pembayaran akan menampilkan tab "12 Get 6", "9 Get 3", dll di landing page.
                </p>
            </div>
            <button onclick="addCategory()"
                    class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                + Tambah Kategori
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 md:p-8">
        <div class="overflow-x-auto">
            <table id="categoryTable" class="table table-zebra w-full text-sm">
                <thead class="bg-purple-100 text-purple-900">
                    <tr>
                        <th class="text-left py-3 px-4">No</th>
                        <th class="text-left py-3 px-4">Nama Kategori</th>
                        <th class="text-left py-3 px-4">Slug</th>
                        <th class="text-left py-3 px-4">Judul Section</th>
                        <th class="text-left py-3 px-4">Subjudul</th>
                        <th class="text-center py-3 px-4">Promo Pembayaran</th>
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
<dialog id="categoryModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-2xl md:text-3xl font-bold text-purple-900" id="modalTitle">Tambah Kategori Baru</h3>
            <button class="btn btn-ghost btn-circle btn-lg" onclick="categoryModal.close()">
                ✕
            </button>
        </div>

        <form id="categoryForm" class="space-y-6">
            @csrf
            <input type="hidden" id="categoryId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Nama Kategori</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full input-lg" placeholder="e.g., Residensial" required>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Slug (untuk identifikasi)</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="slug" class="input input-bordered w-full input-lg" placeholder="e.g., residensial" required>
                </div>
            </div>

            <div>
                <label class="label font-semibold text-gray-700">
                    <span class="label-text">Judul Section di Landing Page</span>
                </label>
                <input type="text" name="description" class="input input-bordered w-full input-lg"
                       placeholder="e.g., Internetan Super Lancar dan Unlimited!">
                <p class="text-xs text-gray-500 mt-1">Akan ditampilkan sebagai judul besar setelah toggle kategori.</p>
            </div>

            <div>
                <label class="label font-semibold text-gray-700">
                    <span class="label-text">Subjudul di Landing Page</span>
                </label>
                <input type="text" name="subtitle" class="input input-bordered w-full input-lg"
                       placeholder="e.g., Hemat Besar Mulai Rp 235.000 - Streaming Lancar, Gaming Tanpa Lag">
                <p class="text-xs text-gray-500 mt-1">Deskripsi pendek di bawah judul utama.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="has_payment_promo" class="checkbox checkbox-primary checkbox-lg">
                        <span class="label-text font-semibold text-gray-700">Punya Promo Pembayaran?</span>
                    </label>
                    <p class="text-xs text-gray-500 ml-12 mt-1">Centang jika kategori ini punya tab seperti "12 Get 6", "9 Get 3", dll.</p>
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-4">
                        <input type="checkbox" name="is_active" class="checkbox checkbox-primary checkbox-lg" checked>
                        <span class="label-text font-semibold text-gray-700">Aktif (tampil di toggle)</span>
                    </label>
                    <p class="text-xs text-gray-500 ml-12 mt-1">Nonaktifkan jika kategori sementara tidak ditampilkan.</p>
                </div>
            </div>

            <div class="modal-action mt-10 flex justify-end gap-4">
                <button type="button" class="btn btn-ghost btn-lg" onclick="categoryModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-lg bg-purple-700 hover:bg-purple-800">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    let table;

    $(document).ready(function() {
        table = $('#categoryTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.categories.data") }}',
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                processing: "Memuat data kategori...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                paginate: { previous: "‹", next: "›" },
                emptyTable: "Belum ada kategori"
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center' },
                { data: 'name', className: 'font-medium' },
                { data: 'slug' },
                { 
                    data: 'description', 
                    render: data => data ? `<div class="max-w-xs truncate">${data}</div>` : '<em class="text-gray-400">Tidak ada</em>'
                },
                { 
                    data: 'subtitle', 
                    render: data => data ? `<div class="max-w-xs truncate">${data}</div>` : '<em class="text-gray-400">Tidak ada</em>'
                },
                { data: 'payment_promo', className: 'text-center' },
                { data: 'status', className: 'text-center' },
                { data: 'action', orderable: false, className: 'text-center whitespace-nowrap' }
            ],
            responsive: true
        });
    });

    window.addCategory = function() {
        form.reset();
        $('#method').val('POST');
        $('#categoryId').val('');
        $('#modalTitle').text('Tambah Kategori Baru');
        modal.showModal();
    };

    window.editCategory = function(id) {
        $.get('/admin/categories/' + id, function(data) {
            $('[name=name]').val(data.name);
            $('[name=slug]').val(data.slug);
            $('[name=description]').val(data.description);
            $('[name=subtitle]').val(data.subtitle);
            $('[name=has_payment_promo]').prop('checked', data.has_payment_promo);
            $('[name=is_active]').prop('checked', data.is_active);
            $('#method').val('PUT');
            $('#categoryId').val(id);
            $('#modalTitle').text('Edit Kategori');
            modal.showModal();
        });
    };

    window.deleteCategory = function(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus kategori ini?',
            text: "Semua paket, tipe paket, dan promo pembayaran terkait akan terdampak!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/categories/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', res.success, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON?.error || 'Tidak dapat menghapus kategori', 'error');
                    }
                });
            }
        });
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#categoryId').val();
        let url = '/admin/categories';

        if (method === 'PUT') {
            url = '/admin/categories/' + id;
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
                const msg = xhr.responseJSON?.message || xhr.responseJSON?.error || 'Gagal menyimpan kategori';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endpush