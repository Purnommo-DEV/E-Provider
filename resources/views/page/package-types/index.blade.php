@extends('layouts.app')

@section('title', 'Manajemen Tipe Paket')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl md:text-4xl font-bold">Manajemen Tipe Paket</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                    Kelola tipe paket yang akan muncul sebagai tab di landing page.<br>
                    Tipe paket bisa bersifat umum (untuk semua promo) atau spesifik untuk promo pembayaran tertentu.
                </p>
            </div>
            <button onclick="addType()"
                    class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                + Tambah Tipe Paket
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 md:p-8">
        <div class="overflow-x-auto">
            <table id="typeTable" class="table table-zebra w-full text-sm">
                <thead class="bg-purple-100 text-purple-900">
                    <tr>
                        <th class="text-left py-3 px-4">No</th>
                        <th class="text-left py-3 px-4">Kategori</th>
                        <th class="text-left py-3 px-4">Promo Pembayaran</th>
                        <th class="text-left py-3 px-4">Nama Tipe Paket</th>
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
<dialog id="typeModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-2xl md:text-3xl font-bold text-purple-900" id="modalTitle">Tambah Tipe Paket</h3>
            <button class="btn btn-ghost btn-circle btn-lg" onclick="typeModal.close()">
                ✕
            </button>
        </div>

        <form id="typeForm" class="space-y-6">
            @csrf
            <input type="hidden" id="typeId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Kategori Utama</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="categorySelect" class="select select-bordered w-full select-lg" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Promo Pembayaran</span>
                    </label>
                    <select name="payment_promo_id" id="promoSelect" class="select select-bordered w-full select-lg">
                        <option value="">Semua Promo (Default)</option>
                        @foreach($paymentPromos as $promo)
                            <option value="{{ $promo->id }}" data-category="{{ $promo->category_id }}">
                                {{ $promo->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hanya promo dari kategori terpilih yang akan muncul</p>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Nama Tipe Paket</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full input-lg"
                           placeholder="e.g., Internet + Streaming" required>
                </div>

                <div class="form-control mt-6">
                    <label class="label cursor-pointer justify-start gap-4">
                        <!-- Hidden default nonaktif -->
                        <input type="hidden" name="supports_streaming_addons" value="0">
                        
                        <!-- Checkbox aktifkan dengan value 1 -->
                        <input type="checkbox" name="supports_streaming_addons" value="1" 
                               class="checkbox checkbox-primary checkbox-lg" 
                               {{ old('supports_streaming_addons', false) ? 'checked' : '' }}>
                        
                        <span class="label-text font-semibold text-gray-700">Mendukung Add-on Streaming Premium</span>
                    </label>
                    <p class="text-xs text-gray-500 ml-12 mt-1">
                        Centang jika tipe paket ini boleh menampilkan add-on seperti Star Movie, Planet Movie, dll.
                    </p>
                </div>
            </div>

            <div class="form-control mt-8">
                <label class="label cursor-pointer justify-start gap-4">
                    <!-- Hidden default nonaktif -->
                    <input type="hidden" name="is_active" value="0">
                    
                    <!-- Checkbox -->
                    <input type="checkbox" name="is_active" value="1" 
                           class="checkbox checkbox-primary checkbox-lg" 
                           checked>
                    
                    <span class="label-text font-semibold text-gray-700">Aktif (tampil di tab tipe paket)</span>
                </label>
                <p class="text-xs text-gray-500 ml-12 mt-1">Nonaktifkan jika tipe paket sementara tidak ingin ditampilkan.</p>
            </div>

            <div class="modal-action mt-10 flex justify-end gap-4">
                <button type="button" class="btn btn-ghost btn-lg" onclick="typeModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-lg bg-purple-700 hover:bg-purple-800">
                    Simpan Tipe Paket
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('typeModal');
    const form = document.getElementById('typeForm');
    let table;

    $(document).ready(function() {
        table = $('#typeTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.package-types.data") }}',
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                processing: "Memuat data tipe paket...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                paginate: { previous: "‹", next: "›" },
                emptyTable: "Belum ada tipe paket"
            },
            columns: [
                { data: 'DT_RowIndex', className: 'text-center' },
                { data: 'category_name', className: 'font-medium' },
                { data: 'promo_name', className: 'font-medium' },
                { data: 'name', className: 'font-medium' },
                { data: 'status', className: 'text-center' },
                { data: 'action', orderable: false, className: 'text-center whitespace-nowrap' }
            ],
            responsive: true
        });

        // Filter promo berdasarkan kategori yang dipilih
        $('#categorySelect').on('change', function() {
            const selectedCat = $(this).val();
            $('#promoSelect option').each(function() {
                const optionCat = $(this).data('category');
                if ($(this).val() === '' || optionCat == selectedCat) {
                    $(this).show();
                } else {
                    $(this).hide();
                    if ($(this).is(':selected')) $(this).prop('selected', false);
                }
            });
            $('#promoSelect').val(''); // reset seleksi promo
        });
    });

    window.addType = function() {
        form.reset();
        $('#method').val('POST');
        $('#typeId').val('');
        $('#modalTitle').text('Tambah Tipe Paket Baru');
        $('#promoSelect').val('');
        modal.showModal();
    };

    window.editType = function(id) {
        $.get('/admin/package-types/' + id, function(data) {
            $('#typeId').val(data.id);
            $('#method').val('PUT');
            $('#categorySelect').val(data.category_id).trigger('change');
            $('#promoSelect').val(data.payment_promo_id ?? '');
            $('[name=name]').val(data.name);
            $('[name=is_active]').prop('checked', data.is_active);
            
            // Tambahkan ini untuk checkbox baru
            $('[name="supports_streaming_addons"]').prop('checked', !!data.supports_streaming_addons);
            
            $('#modalTitle').text('Edit Tipe Paket');
            modal.showModal();
        });
    };

    window.deleteType = function(id) {
        Swal.fire({
            title: 'Yakin ingin menghapus tipe paket ini?',
            text: "Semua paket yang menggunakan tipe ini akan terpengaruh!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/package-types/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', res.success, 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON?.error || 'Tidak dapat menghapus tipe paket', 'error');
                    }
                });
            }
        });
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = $('#method').val();
        const id = $('#typeId').val();
        let url = '/admin/package-types';

        if (method === 'PUT') {
            url = '/admin/package-types/' + id;
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
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan tipe paket';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endpush