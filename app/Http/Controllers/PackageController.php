<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Models\PaymentPromo;
use App\Models\PackageType;
use App\Models\StreamingAddon;

class PackageController extends Controller
{
    public function index()
    {
        return view('page.packages.index', [
            'categories' => Category::all(),
            'paymentPromos' => PaymentPromo::all(),
            'streamingAddons' => StreamingAddon::where('is_active', true)->orderBy('sort_order')->get()
        ]);
    }

    public function data()
    {
        $packages = Package::with([
            'category',
            'packageType',
            'paymentPromo',
            'features'  // ← tambahkan ini agar relasi features ter-load
        ])->select('packages.*');

        return DataTables::of($packages)
            ->addIndexColumn()
            ->addColumn('category', fn($row) => $row->category?->name ?? '-')
            ->addColumn('type', fn($row) => $row->packageType?->name ?? '-')
            ->addColumn('promo', fn($row) => $row->paymentPromo?->name ?? '-')
            ->addColumn('price_rp', fn($row) => 'Rp ' . number_format($row->base_price ?? 0, 0, ',', '.')) // gunakan base_price sesuai migration
            ->addColumn('speed', fn($row) => $row->speed_mbps . ($row->speed_up_to_mbps ? ' / ' . $row->speed_up_to_mbps : '') . ' Mbps')
            ->addColumn('promo_label_preview', fn($row) => $row->promo_label ? '<span class="badge badge-warning">' . $row->promo_label . '</span>' : '-')
            ->addColumn('features_count', fn($row) => '<span class="badge badge-info">' . ($row->features ? $row->features->count() : 0) . '</span>') // ← aman dari null
            ->addColumn('is_active', fn($row) => $row->is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-error">Nonaktif</span>')
            ->addColumn('action', fn($row) => '
                <div class="flex justify-center gap-2">
                    <button onclick="editPackage(' . $row->id . ')" class="btn btn-sm btn-warning text-white"><i class="fa-solid fa-pen-to-square"></i></button>
                    <button onclick="deletePackage(' . $row->id . ')" class="btn btn-sm btn-error"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            ')
            ->rawColumns(['action', 'promo_label_preview', 'features_count', 'is_active'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'package_type_id' => 'required|exists:package_types,id',
            'payment_promo_id' => 'nullable|exists:payment_promos,id',
            'name' => 'required|string|max:255',
            'speed_mbps' => 'required|integer|min:1',
            'speed_up_to_mbps' => 'nullable|integer|min:1',
            'base_price' => 'required|integer|min:0',
            'tax_included' => 'boolean',
            'promo_label' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'has_tv' => 'boolean',
            'channel_count' => 'required_if:has_tv,1|nullable|integer|min:1',
            'stb_info' => 'required_if:has_tv,1|nullable|string|max:255',
            'is_active' => 'boolean',
            // JANGAN validasi repeater di sini, handle manual di bawah
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('packages', 'public');
        }

        // Buat package tanpa repeater
        $package = Package::create($validated);

        // Handle TV/STB
        if ($request->boolean('has_tv')) {
            $package->update([
                'channel_count' => $request->input('channel_count'),
                'stb_info' => $request->input('stb_info'),
            ]);
        }

        // Handle streaming add-ons (pivot)
        if ($request->has('streaming_addons')) {
            $package->streamingAddons()->sync($request->input('streaming_addons'));
        }

        // Handle benefits (pivot)
        if ($request->has('benefits')) {
            foreach ($request->input('benefits') as $benefitData) {
                $package->benefits()->attach($benefitData['benefit_id'], [
                    'duration_value' => $benefitData['duration_value'],
                    'duration_unit' => $benefitData['duration_unit'],
                ]);
            }
        }

        // Handle features (hasMany ke package_features)
        if ($request->has('features')) {
            foreach ($request->input('features') as $index => $featureData) {
                $package->features()->create([
                    'label' => $featureData['label'],
                    'icon' => $featureData['icon'] ?? null,
                    'sort_order' => $featureData['sort_order'] ?? $index,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Paket berhasil ditambahkan']);
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'package_type_id' => 'required|exists:package_types,id',
            'payment_promo_id' => 'nullable|exists:payment_promos,id',
            'name' => 'required|string|max:255',
            'speed_mbps' => 'required|integer|min:1',
            'speed_up_to_mbps' => 'nullable|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'promo_label' => 'nullable|string|max:255',
            'tax_included' => 'boolean',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'has_tv' => 'boolean',
            'channel_count' => 'required_if:has_tv,1|nullable|integer|min:1',
            'stb_info' => 'required_if:has_tv,1|nullable|string|max:255',
            // Repeater & pivot tidak masuk ke validated model utama
        ]);

        // Handle upload image baru
        if ($request->hasFile('image')) {
            if ($package->image) {
                Storage::disk('public')->delete($package->image);
            }
            $validated['image'] = $request->file('image')->store('packages', 'public');
        }

        // Update field utama package (tanpa repeater)
        $package->update($validated);

        // Handle field TV/STB (opsional)
        if ($request->boolean('has_tv')) {
            $package->update([
                'channel_count' => $request->input('channel_count'),
                'stb_info' => $request->input('stb_info'),
            ]);
        } else {
            // Kosongkan jika tidak ada TV
            $package->update([
                'channel_count' => null,
                'stb_info' => null,
            ]);
        }

        // Handle streaming add-ons (pivot many-to-many)
        $package->streamingAddons()->sync($request->input('streaming_addons', []));

        // Handle benefits (pivot many-to-many dengan pivot fields)
        $package->benefits()->detach(); // hapus dulu semua
        if ($request->has('benefits') && is_array($request->input('benefits'))) {
            foreach ($request->input('benefits') as $benefitData) {
                if (!empty($benefitData['benefit_id'])) {
                    $package->benefits()->attach($benefitData['benefit_id'], [
                        'duration_value' => $benefitData['duration_value'] ?? 12,
                        'duration_unit' => $benefitData['duration_unit'] ?? 'BULAN',
                    ]);
                }
            }
        }

        // Handle features (hasMany, hapus lalu buat ulang)
        $package->features()->delete(); // hapus semua features lama
        if ($request->has('features') && is_array($request->input('features'))) {
            foreach ($request->input('features') as $index => $featureData) {
                if (!empty($featureData['label'])) {
                    $package->features()->create([
                        'label' => $featureData['label'],
                        'icon' => $featureData['icon'] ?? null,
                        'sort_order' => $featureData['sort_order'] ?? $index,
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil diperbarui'
        ]);
    }

    public function destroy(Package $package)
    {
        if ($package->image) Storage::disk('public')->delete($package->image);
        $package->delete();
        return response()->json(['success' => true, 'message' => 'Paket berhasil dihapus']);
    }

    public function show(Package $package)
    {
        // Load relasi yang benar-benar dibutuhkan di frontend
        $package->load([
            'category' => fn($q) => $q->select('id', 'name'),
            'packageType' => fn($q) => $q->select('id', 'name', 'supports_streaming_addons'),
            'paymentPromo' => fn($q) => $q->select('id', 'name'),
            'features' => fn($q) => $q->select('id', 'package_id', 'label', 'icon', 'sort_order')->orderBy('sort_order'),
            // Benefits & streaming add-ons juga bisa diload jika frontend butuh
            'benefits' => fn($q) => $q->select('benefits.id', 'name', 'category')->withPivot('duration_value', 'duration_unit'),
            'streamingAddons' => fn($q) => $q->select('streaming_addons.id', 'name'),
        ]);

        return response()->json($package);
    }

    // Dropdown
    public function getTypes($categoryId)
    {
        $types = PackageType::where('category_id', $categoryId)
            ->select('id', 'name', 'payment_promo_id')
            ->orderBy('name')
            ->get();
        return response()->json($types);
    }

    public function getPromosByCategory($categoryId)
    {
        $promos = PaymentPromo::where('category_id', $categoryId)
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get(['id', 'name', 'months_paid', 'months_free']);
        return response()->json($promos);
    }
}