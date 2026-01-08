@extends('layouts.app')

@section('title', 'Manajemen Add-on Streaming Premium')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-2xl md:text-4xl font-bold">Manajemen Add-on Streaming Premium</h3>
                <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                    Kelola add-on streaming seperti Star Movie, Planet Movie, Rocket Sports, Galaxy Ultimate.<br>
                    Add-on ini akan muncul sebagai tombol pill ungu pada paket tertentu di landing page.
                </p>
            </div>
            <button onclick="addAddon()"
                    class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Add-on
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 md:p-8">
        <div class="overflow-x-auto">
            <table id="addonTable" class="table table-zebra w-full text-sm">
                <thead class="bg-purple-100 text-purple-900">
                    <tr>
                        <th class="text-left py-3 px-4">Nama Add-on</th>
                        <th class="text-left py-3 px-4">Key</th>
                        <th class="text-center py-3 px-4">Warna</th>
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

<!-- Modal Form -->
<dialog id="addonModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-4xl bg-white">
        <div class="flex justify-between items-center mb-8 border-b pb-4">
            <h3 class="text-2xl md:text-3xl font-bold text-purple-900" id="modalTitle">Tambah Add-on Streaming</h3>
            <button class="btn btn-ghost btn-circle btn-lg" onclick="addonModal.close()">
                ✕
            </button>
        </div>

        <form id="addonForm" class="space-y-6">
            @csrf
            <input type="hidden" id="addonId">
            <input type="hidden" id="method" value="POST">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Nama Add-on</span>
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" class="input input-bordered w-full input-lg" 
                           placeholder="contoh: Star Movie" required>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Warna Background (hex)</span>
                    </label>
                    <input type="color" name="color" id="color" class="input input-bordered w-32 h-12 p-1" value="#8B5CF6">
                    <p class="text-xs text-gray-500 mt-1">Default: ungu (#8B5CF6)</p>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">
                        <span class="label-text">Urutan Tampil</span>
                    </label>
                    <input type="number" name="sort_order" id="sort_order" class="input input-bordered w-full input-lg" 
                           min="0" value="0">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label font-semibold text-gray-700">Icon (FontAwesome class, opsional)</label>
                    <input type="text" name="icon" id="icon" class="input input-bordered w-full" 
                           placeholder="contoh: fa-star, fa-film">
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">Deskripsi (opsional)</label>
                    <textarea name="description" id="description" class="textarea textarea-bordered w-full" rows="3"
                              placeholder="Deskripsi singkat add-on ini..."></textarea>
                </div>
            </div>

            <!-- Sisanya sama -->
            <div class="form-control mt-8">
                <label class="label cursor-pointer justify-start gap-4">
                    <!-- Hidden default 0, akan di-override jika checkbox dicentang -->
                    <input type="hidden" name="is_active" value="0">
                    
                    <input type="checkbox" name="is_active" value="1" 
                           class="checkbox checkbox-primary checkbox-lg" 
                           {{ old('is_active', 1) ? 'checked' : '' }}>
                    
                    <span class="label-text font-semibold text-gray-700">Aktif (tampil di paket)</span>
                </label>
                <p class="text-xs text-gray-500 ml-12 mt-1">Nonaktifkan jika add-on sementara tidak ingin ditawarkan.</p>
            </div>

            <div class="modal-action mt-10 flex justify-end gap-4">
                <button type="button" class="btn btn-ghost btn-lg" onclick="addonModal.close()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-lg bg-purple-700 hover:bg-purple-800">
                    <i class="fa-solid fa-save mr-2"></i> Simpan Add-on
                </button>
            </div>
        </form>
    </div>
</dialog>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('addonModal');
    const form = document.getElementById('addonForm');
    let table;

    $(document).ready(function() {
        table = $('#addonTable').DataTable({
            processing: true,
            ajax: '{{ route("admin.streaming-addons.data") }}',
            dom: '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
            language: {
                processing: "Memuat data add-on...",
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_",
                paginate: { previous: "‹", next: "›" },
                emptyTable: "Belum ada add-on streaming"
            },
            columns: [
                { data: 'name', className: 'font-medium' },
                { data: 'key', className: 'font-medium' },
                { 
                    data: 'color', 
                    className: 'text-center',
                    render: function(data) {
                        return `<div class="w-8 h-8 mx-auto rounded-full border border-gray-300" style="background-color:${data}"></div>`;
                    }
                },
                { data: 'sort_order', className: 'text-center' },
                { 
                    data: 'is_active',
                    className: 'text-center',
                    render: function(data) {
                        return data 
                            ? '<span class="badge badge-success badge-lg">Aktif</span>' 
                            : '<span class="badge badge-error badge-lg">Nonaktif</span>';
                    }
                },
                { 
                    data: null,
                    className: 'text-center whitespace-nowrap',
                    render: function(data, type, row) {
                        return `
                            <button onclick="editAddon(${row.id})" class="btn btn-sm btn-warning text-white">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button onclick="deleteAddon(${row.id})" class="btn btn-sm btn-error">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        `;
                    },
                    orderable: false
                }
            ],
            responsive: true
        });
    });

    window.addAddon = function() {
        form.reset();
        document.getElementById('method').value = 'POST';
        document.getElementById('addonId').value = '';
        document.getElementById('modalTitle').textContent = 'Tambah Add-on Streaming';
        
        // Safe set default values dengan null check
        const colorEl = document.getElementById('color');
        const sortOrderEl = document.getElementById('sort_order');
        if (colorEl) colorEl.value = '#8B5CF6';
        if (sortOrderEl) sortOrderEl.value = '0';
        
        const isActiveEl = document.getElementById('is_active');
        if (isActiveEl) isActiveEl.checked = true;
        
        modal.showModal();
    };

    window.editAddon = function(id) {
        $.get('/admin/streaming-addons/' + id, function(data) {
            document.getElementById('addonId').value = data.id;
            document.getElementById('method').value = 'PUT';
            document.getElementById('modalTitle').textContent = 'Edit Add-on Streaming';

            // Safe set values dengan querySelector + null check
            const nameEl = document.querySelector('[name="name"]');
            const colorEl = document.getElementById('color');
            const iconEl = document.getElementById('icon');
            const descEl = document.getElementById('description');
            const sortEl = document.getElementById('sort_order');
            const activeEl = document.getElementById('is_active');

            if (nameEl) nameEl.value = data.name || '';
            if (colorEl) colorEl.value = data.color || '#8B5CF6';
            if (iconEl) iconEl.value = data.icon || '';
            if (descEl) descEl.value = data.description || '';
            if (sortEl) sortEl.value = data.sort_order || 0;
            if (activeEl) activeEl.checked = !!data.is_active;

            modal.showModal();
        }).fail(() => {
            Swal.fire('Gagal', 'Tidak bisa memuat data add-on', 'error');
        });
    };

    window.deleteAddon = function(id) {
        Swal.fire({
            title: 'Yakin hapus add-on ini?',
            text: "Add-on akan dihapus dari semua paket yang menggunakannya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/streaming-addons/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(res) {
                        table.ajax.reload();
                        Swal.fire('Terhapus!', res.success || 'Add-on berhasil dihapus', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Gagal!', xhr.responseJSON?.error || 'Tidak dapat menghapus', 'error');
                    }
                });
            }
        });
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const method = document.getElementById('method').value;
        const id = document.getElementById('addonId').value;
        let url = '/admin/streaming-addons';
        if (method === 'PUT') {
            url += '/' + id;
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
                Swal.fire('Sukses!', res.success || 'Add-on berhasil disimpan', 'success');
            },
            error: function(xhr) {
                const msg = xhr.responseJSON?.message || 'Gagal menyimpan add-on';
                Swal.fire('Error', msg, 'error');
            }
        });
    });
</script>
@endpush