@extends('layouts.app')

@section('title', 'Kelola Hiburan Terlengkap (TV Add-on)')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Header Card -->
        <div class="bg-gradient-to-r from-purple-800 to-purple-900 text-white p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="text-2xl md:text-4xl font-bold">Kelola Hiburan Terlengkap (TV Add-on)</h3>
                    <p class="text-purple-200 mt-3 text-sm md:text-base opacity-90">
                        Pengaturan utama section hiburan di landing page: harga, teks, dan gambar device.<br>
                        Kelola channel & platform streaming di menu terpisah.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button onclick="editTvAddon()"
                            class="bg-orange-500 hover:bg-orange-600 text-purple-950 font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-edit mr-2"></i> Edit Pengaturan TV Add-on
                    </button>
                    <button onclick="manageChannels()"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                        <i class="fas fa-tv mr-2"></i> Kelola Channel & Platform
                    </button>
                </div>
            </div>
        </div>

        <!-- Preview Section (Mirip Landing Page) -->
        <div class="p-6 md:p-10 bg-gradient-to-b from-purple-50 to-white">
            <div class="max-w-6xl mx-auto">
                <p class="text-purple-600 text-sm md:text-base mb-2">Akses Hiburan Terlengkap</p>
                <h2 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    {{ $tvAddon->title ?? 'Hiburan Terlengkap Untuk Keluarga' }}
                </h2>
                <p class="text-lg md:text-xl text-gray-700 mb-8">
                    {{ $tvAddon->subtitle ?? 'Nikmati channel dan platform video streaming terlengkap' }}
                </p>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Teks & Harga -->
                    <div class="text-center lg:text-left">
                        <p class="text-4xl md:text-6xl font-extrabold text-purple-900 mb-4">
                            Mulai dari<br>
                            @if($tvAddon->price_text)
                                <span class="text-cyan-500">{{ $tvAddon->price_text }}</span>
                            @else
                                <span class="text-cyan-500">Rp {{ number_format($tvAddon->price ?? 20000, 0, ',', '.') }}</span>
                                <span class="text-3xl">/bulan</span>
                            @endif
                        </p>

                        <p class="text-lg md:text-xl text-gray-700 mt-12">
                            {{ $tvAddon->description ?? 'Akses ke lebih dari 70+ Channel TV Lokal maupun Internasional dan Live Event lainnya' }}
                        </p>
                    </div>

                    <!-- Gambar Device -->
                    <div class="flex justify-center">
                        @if($tvAddon->device_image)
                            <img src="{{ asset('storage/' . $tvAddon->device_image) }}"
                                 alt="MyRepublic Pride TV Box + Remote"
                                 class="max-w-full h-auto rounded-2xl shadow-2xl">
                        @else
                            <div class="bg-gray-200 border-2 border-dashed rounded-2xl w-96 h-80 flex items-center justify-center">
                                <p class="text-gray-500 text-center">Belum ada gambar device<br>Upload di pengaturan TV Add-on</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Grid Channel Preview -->
                @if($channels->count() > 0)
                    <div class="mt-16">
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-8 max-w-5xl mx-auto">
                            @foreach($channels->take(12) as $channel)
                                <div class="flex flex-col items-center group">
                                    @if($channel->logo)
                                        <div class="relative">
                                            <img src="{{ asset('storage/' . $channel->logo) }}"
                                                 alt="{{ $channel->name }}"
                                                 class="w-24 h-24 object-contain rounded-lg shadow-md group-hover:shadow-xl transition">
                                            <span class="absolute -right-2 -bottom-2 text-2xl opacity-80 group-hover:opacity-100 transition">→</span>
                                        </div>
                                    @else
                                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center shadow-md">
                                            <span class="text-gray-500 text-xs text-center px-2">{{ $channel->name }}</span>
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-600 mt-2 text-center">{{ $channel->name }}</p>
                                </div>
                            @endforeach
                        </div>
                        @if($channels->count() > 12)
                            <p class="text-center text-gray-600 mt-8">... dan {{ $channels->count() - 12 }} channel lainnya</p>
                        @endif
                    </div>
                @else
                    <div class="text-center py-16">
                        <p class="text-gray-500 text-lg">Belum ada channel/platform yang ditambahkan.<br>
                        Tambahkan di menu "Kelola Channel & Platform"</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Edit Pengaturan TV Add-on -->
    <dialog id="tvAddonModal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box w-11/12 max-w-5xl bg-white rounded-2xl shadow-2xl">
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h3 class="text-3xl font-extrabold text-purple-900">Pengaturan Hiburan Terlengkap</h3>
                <button class="btn btn-ghost btn-circle text-2xl hover:bg-gray-200" onclick="tvAddonModal.close()">✕</button>
            </div>

            <form id="tvAddonForm" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="label font-semibold text-gray-700">Judul Utama</label>
                        <input type="text" name="title" value="{{ $tvAddon->title ?? 'Hiburan Terlengkap Untuk Keluarga' }}"
                               class="input input-bordered w-full input-lg rounded-xl shadow-sm" required>
                    </div>
                    <div>
                        <label class="label font-semibold text-gray-700">Subtitle</label>
                        <input type="text" name="subtitle" value="{{ $tvAddon->subtitle ?? 'Nikmati channel dan platform video streaming terlengkap' }}"
                               class="input input-bordered w-full input-lg rounded-xl shadow-sm">
                    </div>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">Deskripsi</label>
                    <textarea name="description" class="textarea textarea-bordered w-full h-32 input-lg rounded-xl shadow-sm">{{ $tvAddon->description ?? 'Akses ke lebih dari 70+ Channel TV Lokal maupun Internasional dan Live Event lainnya' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="label font-semibold text-gray-700">Harga Angka (Rp)</label>
                        <input type="number" name="price" value="{{ $tvAddon->price ?? 20000 }}"
                               class="input input-bordered w-full input-lg rounded-xl shadow-sm" min="0" required>
                    </div>
                    <div>
                        <label class="label font-semibold text-gray-700">Teks Harga Custom</label>
                        <input type="text" name="price_text" value="{{ $tvAddon->price_text ?? '' }}"
                               class="input input-bordered w-full input-lg rounded-xl shadow-sm"
                               placeholder="e.g., Mulai dari Rp 20 ribuan / bulan">
                        <p class="text-xs text-gray-500 mt-1">Jika diisi, akan menggantikan tampilan harga angka</p>
                    </div>
                    <div>
                        <label class="label font-semibold text-gray-700">Jumlah Channel</label>
                        <input type="number" name="channel_count" value="{{ $tvAddon->channel_count ?? 70 }}"
                               class="input input-bordered w-full input-lg rounded-xl shadow-sm" min="0" required>
                    </div>
                </div>

                <div>
                    <label class="label font-semibold text-gray-700">Gambar Device (TV Box + Remote)</label>
                    <p class="text-sm text-gray-500 mb-3">Rekomendasi: 800x600 px atau lebih, rasio landscape</p>
                    <input type="file" name="device_image"
                           class="file-input file-input-bordered file-input-primary w-full h-14 rounded-xl shadow-sm hover:shadow-md transition-all"
                           accept="image/*">

                    @if($tvAddon->device_image)
                        <div class="mt-6">
                            <p class="text-sm font-medium text-gray-600 mb-3">Gambar Saat Ini:</p>
                            <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-purple-100 inline-block">
                                <img src="{{ asset('storage/' . $tvAddon->device_image) }}"
                                     class="max-w-full h-auto max-h-96 object-contain" alt="Current Device Image">
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-action flex justify-end gap-4 pt-6 border-t">
                    <button type="button" class="btn btn-ghost btn-lg rounded-xl px-8 shadow-md hover:shadow-lg" onclick="tvAddonModal.close()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary bg-gradient-to-r from-purple-700 to-purple-800 hover:from-purple-800 hover:to-purple-900 text-white btn-lg rounded-xl px-10 shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <i class="fas fa-save mr-3"></i>
                        Simpan Pengaturan
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
@endpush

@push('scripts')
    <script>
        const tvAddonModal = document.getElementById('tvAddonModal');

        window.editTvAddon = function() {
            tvAddonModal.showModal();
        };

        window.manageChannels = function() {
            window.location.href = '{{ route("admin.channels.index") }}';
        };

        // Preview gambar baru - DIPERBAIKI
        $('[name="device_image"]').change(function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let previewContainer = $('#newDevicePreview');

                    // Jika belum ada preview, buat dan letakkan di tempat yang benar
                    if (previewContainer.length === 0) {
                        // Cari parent dari input file (div yang berisi label + input)
                        const fileInputWrapper = $('[name="device_image"]').closest('div');

                        // Tambahkan preview tepat setelah current image (jika ada) atau setelah input
                        fileInputWrapper.append(`
                            <div id="newDevicePreview" class="mt-6">
                                <p class="text-sm font-medium text-gray-600 mb-3">Pratinjau Gambar Baru:</p>
                                <div class="relative rounded-2xl overflow-hidden shadow-xl border-4 border-purple-100 inline-block">
                                    <img src="${e.target.result}" class="max-w-full h-auto max-h-96 object-contain rounded-xl" alt="Preview Gambar Baru">
                                </div>
                            </div>
                        `);
                    } else {
                        // Jika sudah ada, hanya update src gambar
                        previewContainer.find('img').attr('src', e.target.result);
                    }
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                // Jika file dihapus, hapus preview juga
                $('#newDevicePreview').remove();
            }
        });

        // Submit form (tidak berubah)
        $('#tvAddonForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html('<span class="loading loading-spinner"></span> Menyimpan...');

            $.ajax({
                url: '{{ route("admin.tv-addon.update") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    tvAddonModal.close();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: res.success || 'Pengaturan berhasil diperbarui',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => location.reload());
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Gagal menyimpan pengaturan';
                    Swal.fire('Error', msg, 'error');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    </script>
@endpush