<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager; // <-- NEW: Import ImageManager
use Intervention\Image\Drivers\Gd\Driver; // atau Imagick\Driver jika pakai Imagick

class BannerController extends Controller
{
    /**
     * Tampilkan halaman index page
     */
    public function index()
    {
        return view('page.banners.index');
    }

    /**
     * Data untuk DataTables
     */
    public function data()
    {
        $banners = Banner::orderBy('order', 'asc')->get();

        return datatables()->of($banners)
            ->addIndexColumn()
            ->addColumn('image_preview', function ($banner) {
                return '<img src="' . asset('storage/' . $banner->image) . '" class="w-48 h-32 object-cover rounded-lg shadow" alt="Banner">';
            })
            ->addColumn('status', function ($banner) {
                return $banner->is_active
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-error">Nonaktif</span>';
            })
            ->addColumn('action', function ($banner) {
                return '
                    <button onclick="editBanner(' . $banner->id . ')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="deleteBanner(' . $banner->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
                ';
            })
            ->rawColumns(['image_preview', 'status', 'action'])
            ->make(true);
    }

    /**
     * Simpan banner baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean', // opsional validasi
        ]);

        $manager = new ImageManager(new Driver());

        $uploadedImage = $request->file('image');

        $image = $manager->read($uploadedImage)
                         ->resize(1920, 800, function ($constraint) {
                             $constraint->aspectRatio();
                             $constraint->upsize();
                         })
                         ->toWebp(85);

        $filename = 'banner_' . time() . '_' . uniqid() . '.webp';
        $path = 'banners/' . $filename;

        Storage::disk('public')->put($path, $image);

        $order = $request->filled('order') ? $request->order : (Banner::max('order') + 1 ?? 0);

        Banner::create([
            'image' => $path,
            'order' => $order,
            'is_active' => $request->boolean('is_active'), // Cara paling aman & recommended
            // 'is_active' => $request->boolean('is_active'), // Laravel 9+ helper terbaik!
        ]);

        return response()->json(['success' => 'Banner berhasil ditambahkan']);
    }

    /**
     * Tampilkan data banner untuk edit
     */
    public function show(Banner $banner)
    {
        return response()->json([
            'id'        => $banner->id,
            'image'     => $banner->image,
            'order'     => $banner->order,
            'is_active' => $banner->is_active,
        ]);
    }

    /**
     * Update banner
     */
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = [
            'is_active' => $request->boolean('is_active'), // Cara paling aman & recommended
        ];

        if ($request->filled('order')) {
            $data['order'] = $request->order;
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $manager = new ImageManager(new Driver());
            $uploadedImage = $request->file('image');

            $image = $manager->read($uploadedImage)
                             ->resize(1920, 800, function ($constraint) {
                                 $constraint->aspectRatio();
                                 $constraint->upsize();
                             })
                             ->toWebp(85);

            $filename = 'banner_' . time() . '_' . uniqid() . '.webp';
            $path = 'banners/' . $filename;

            Storage::disk('public')->put($path, $image);
            $data['image'] = $path;
        }

        $banner->update($data);

        return response()->json(['success' => 'Banner berhasil diperbarui']);
    }

    /**
     * Hapus banner
     */
    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return response()->json(['success' => 'Banner berhasil dihapus']);
    }
}