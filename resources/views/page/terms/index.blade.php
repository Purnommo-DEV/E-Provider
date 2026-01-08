@extends('layouts.app')
@section('title', 'Edit Syarat dan Ketentuan')


@push('styles')
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    /* ===== FIX TRIX EDITOR ===== */
    trix-editor ul {
        list-style: disc;
        padding-left: 1.5rem;
    }

    trix-editor ol {
        list-style: decimal;
        padding-left: 1.5rem;
    }

    trix-editor li {
        margin-bottom: 0.5rem;
    }

    trix-editor p {
        margin-bottom: 0.75rem;
    }
    
    .trix-content ul {
        list-style: disc;
        padding-left: 1.5rem;
    }

    .trix-content li {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-2xl shadow-xl overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
        <h3 class="text-2xl md:text-4xl font-bold">Edit Syarat dan Ketentuan</h3>
        <p class="text-purple-200 mt-4 text-sm md:text-base opacity-90">
            Konten di bawah ini akan ditampilkan di bagian accordion "Syarat dan Ketentuan" pada landing page.<br>
            Gunakan toolbar editor untuk format teks: **bold**, *italic*, bullet points, numbered list, link, heading, dll.
        </p>
    </div>
    <!-- Form -->
    <div class="p-6 md:p-10">
        <form id="termForm">
            @csrf
            <div class="mb-10">
            <label class="label font-semibold text-gray-700 text-lg">
                <span class="label-text">Isi Syarat dan Ketentuan</span>
            </label>
            <!-- Trix Editor -->
            <trix-editor
                input="contentInput"
                class="h-96 bg-white border-2 border-gray-300 rounded-xl focus:border-purple-600 focus:ring-4 focus:ring-purple-200 transition-all"
                placeholder="Tulis syarat dan ketentuan di sini...">
            </trix-editor>

            <input
                type="hidden"
                name="content"
                id="contentInput"
                value="{{ old('content', $term->content ?? '') }}">

                <p class="text-sm text-gray-600 mt-3">
                    Tips: Gunakan tombol **Bullet List** atau **Numbered List** untuk membuat poin seperti di situs MyRepublic.
                </p>
            </div>
            <!-- Toggle Tampilkan -->
            <div class="form-control mb-10">
                <div class="prose max-w-none">
                    {!! $term->content !!}
                </div>
                <label class="label cursor-pointer justify-start gap-4">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="checkbox checkbox-primary checkbox-lg"
                           {{ old('is_active', $term->is_active) ? 'checked' : '' }}>
                    <span class="label-text font-semibold text-gray-700 text-lg">
                        Tampilkan di Landing Page
                    </span>
                </label>
                <p class="text-sm text-gray-500 ml-12 mt-1">
                    Nonaktifkan jika sedang ada update besar atau promo baru.
                </p>
            </div>
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                        class="btn btn-primary btn-lg bg-purple-700 hover:bg-purple-800 text-white font-bold px-10 shadow-lg transition transform hover:scale-105">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Notifikasi -->
<div id="notification" class="toast toast-top toast-center hidden">
    <div class="alert alert-success" id="notificationAlert">
        <span id="notificationMessage"></span>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('termForm');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('{{ route("admin.terms.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            showNotification(data.success || data.message, 'success');
        })
        .catch(err => {
            showNotification('Terjadi kesalahan saat menyimpan data.', 'error');
            console.error(err);
        });
    });

    function showNotification(message, type = 'success') {
        notificationMessage.textContent = message;
        notificationAlert.classList.remove('alert-success', 'alert-error');
        notificationAlert.classList.add(type === 'success' ? 'alert-success' : 'alert-error');
        notification.classList.remove('hidden');
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 5000);
    }

    // Custom toolbar Trix
    document.addEventListener("trix-initialize", function(event) {
        const toolbar = event.target.toolbarElement;
        [
            'strike','quote','code','undo','redo',
            'decreaseNestingLevel','increaseNestingLevel','attachFiles'
        ].forEach(name => {
            const btn = toolbar.querySelector(`.trix-button--icon-${name}`);
            if (btn) btn.style.display = 'none';
        });
    });
});
</script>
@endpush