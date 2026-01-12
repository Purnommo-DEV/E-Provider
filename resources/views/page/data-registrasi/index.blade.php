@extends('layouts.app')
@section('title', 'Data Registrasi')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-800 text-white p-3 md:p-5">
        <div class="flex justify-between items-center">
            <h3 class="text-lg md:text-2xl font-bold">Data Registrasi</h3>
        </div>
    </div>

    <div class="p-3 md:p-5">
        <div class="overflow-x-auto">
            <table id="leadTable" class="table table-zebra w-full text-sm">
                <thead class="bg-emerald-100 text-emerald-900">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>IP</th>
                        <th>Waktu</th>
                        <th class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">

<script>
let table;

$(document).ready(function () {
    table = $('#leadTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('lead.data') }}',
        columns: [
            { data: 'name' },
            { data: 'email', defaultContent: '-' },
            { data: 'phone' },
            { data: 'ip_address' },
            { data: 'created_at' },
            {
                data: 'id',
                className: 'text-center',
                render: function (id) {
                    return `
                        <button onclick="deleteLead(${id})"
                            class="btn btn-error btn-sm">
                            Hapus
                        </button>
                    `;
                }
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_",
            paginate: { previous: "‹", next: "›" }
        }
    });
});

function deleteLead(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        text: 'Data registrasi akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/leads/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function () {
                    table.ajax.reload();
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success');
                }
            });
        }
    });
}
</script>
@endpush
