@extends('layouts.app')

@section('title', 'Kelola Benefit / OTT')

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6">
        <div class="flex justify-between items-center">
            <h3 class="text-2xl font-bold">Kelola Benefit / OTT</h3>
            <button onclick="addBenefit()"
                class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg">
                <i class="fas fa-plus mr-2"></i> Tambah Benefit
            </button>
        </div>
    </div>

    <div class="p-6">
        <table id="benefitTable" class="table table-lg w-full">
            <thead class="bg-purple-50">
                <tr>
                    <th>No</th>
                    <th>Logo</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<dialog id="benefitModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="text-2xl font-bold mb-6" id="modalTitle">Tambah Benefit</h3>

        <form id="benefitForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="benefitId">
            <input type="hidden" id="method" value="POST">

            <div class="space-y-4">
                <div>
                    <label class="label">Nama Benefit</label>
                    <input type="text" name="name" class="input input-bordered w-full" required>
                </div>

                <div>
                    <label class="label">Kategori</label>
                    <select name="category" class="select select-bordered w-full">
                        <option value="ott">OTT</option>
                        <option value="bonus">Bonus</option>
                    </select>
                </div>

                <div>
                    <label class="label">Logo</label>
                    <input type="file" name="logo" class="file-input file-input-bordered w-full">
                </div>
            </div>

            <div class="modal-action mt-8">
                <button type="button" class="btn" onclick="benefitModal.close()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>

@endsection

@push('scripts')
<script>
let table;
const modal = document.getElementById('benefitModal');
const form = document.getElementById('benefitForm');

$(function () {
    table = $('#benefitTable').DataTable({
        processing: true,
        ajax: '{{ route("admin.benefits.data") }}',
        columns: [
            { data: null, render: (d,t,r,m)=>m.row+m.settings._iDisplayStart+1 },
            { data: 'logo_preview', orderable:false, className:'text-center' },
            { data: 'name' },
            { data: 'category_label', className:'text-center' },
            { data: 'action', orderable:false }
        ]
    });
});

function addBenefit() {
    form.reset();
    $('#benefitId').val('');
    $('#method').val('POST');
    $('#modalTitle').text('Tambah Benefit');
    modal.showModal();
}

function editBenefit(id) {
    $.get('/admin/benefits/' + id, res => {
        $('#benefitId').val(res.id);
        $('[name=name]').val(res.name);
        $('[name=category]').val(res.category);
        $('#method').val('PUT');
        $('#modalTitle').text('Edit Benefit');
        modal.showModal();
    });
}

function deleteBenefit(id) {
    Swal.fire({
        title: 'Yakin hapus?',
        icon: 'warning',
        showCancelButton: true
    }).then(r => {
        if (r.isConfirmed) {
            $.ajax({
                url: '/admin/benefits/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: () => table.ajax.reload()
            });
        }
    });
}

form.addEventListener('submit', e => {
    e.preventDefault();

    let formData = new FormData(form);
    let id = $('#benefitId').val();
    let method = $('#method').val();
    let url = '/admin/benefits';

    if (method === 'PUT') {
        url += '/' + id;
        formData.append('_method', 'PUT');
    }

    $.ajax({
        url,
        type: 'POST',
        data: formData,
        processData:false,
        contentType:false,
        success: () => {
            modal.close();
            table.ajax.reload();
        }
    });
});
</script>
@endpush
