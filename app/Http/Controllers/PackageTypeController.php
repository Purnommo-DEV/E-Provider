<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PackageType;
use App\Models\Category;
use App\Models\PaymentPromo;
use Illuminate\Http\Request;

class PackageTypeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $paymentPromos = PaymentPromo::where('is_active', true)->get();

        return view('page.package-types.index', compact('categories', 'paymentPromos'));
    }

    public function data()
    {
        $types = PackageType::with(['category', 'paymentPromo'])->orderBy('order', 'asc')->get();
        return datatables()->of($types)
            ->addIndexColumn()
            ->addColumn('category_name', fn($type) => $type->category?->name ?? '-')
            ->addColumn('promo_name', fn($type) => $type->paymentPromo?->name ?? 'Semua Promo (Default)')
            ->addColumn('status', fn($type) => $type->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-error">Nonaktif</span>')
            ->addColumn('action', fn($type) => '
                <button onclick="editType(' . $type->id . ')" class="btn btn-warning btn-sm">Edit</button>
                <button onclick="deleteType(' . $type->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
            ')
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'payment_promo_id' => 'nullable|exists:payment_promos,id',
            'name' => 'required|string|max:255',
            'is_active'                 => 'nullable|in:0,1',
            'supports_streaming_addons' => 'nullable|in:0,1',
        ]);

        PackageType::create($validated);

        return response()->json(['success' => 'Tipe paket berhasil ditambahkan']);
    }

    public function update(Request $request, PackageType $packageType)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'payment_promo_id' => 'nullable|exists:payment_promos,id',
            'name' => 'required|string|max:255',
            'is_active'                 => 'nullable|in:0,1',
            'supports_streaming_addons' => 'nullable|in:0,1',
        ]);

        $packageType->update($validated);

        return response()->json(['success' => 'Tipe paket berhasil diupdate']);
    }

    public function show(PackageType $packageType)
    {
        $packageType->load(['category', 'paymentPromo']);
        return response()->json($packageType);
    }

    public function destroy(PackageType $packageType)
    {
        if ($packageType->packages()->count() > 0) {
            return response()->json(['error' => 'Tidak bisa dihapus karena ada paket yang menggunakan tipe ini'], 422);
        }

        $packageType->delete();
        return response()->json(['success' => 'Tipe paket berhasil dihapus']);
    }
}