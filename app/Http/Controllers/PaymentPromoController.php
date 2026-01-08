<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PaymentPromo;
use App\Models\Category;
use Illuminate\Http\Request;

class PaymentPromoController extends Controller
{
    public function index()
    {
        $categories = Category::where('has_payment_promo', true)->get();
        return view('page.payment-promos.index', compact('categories'));
    }

    public function data()
    {
        $promos = PaymentPromo::with('category')->orderBy('order', 'asc')->get();

        return datatables()->of($promos)
            ->addIndexColumn()
            ->addColumn('category_name', function ($promo) {
                return $promo->category ? $promo->category->name : '-';
            })
            ->addColumn('detail', function ($promo) {
                return 'Bayar ' . $promo->months_paid . ' bulan, Gratis ' . $promo->months_free . ' bulan';
            })
            ->addColumn('status', function ($promo) {
                return $promo->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-error">Nonaktif</span>';
            })
            ->addColumn('action', function ($promo) {
                return '
                    <button onclick="editPromo(' . $promo->id . ')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="deletePromo(' . $promo->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'months_paid' => 'required|integer|min:1',
            'months_free' => 'required|integer|min:0',
        ]);

        PaymentPromo::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'months_paid' => $request->months_paid,
            'months_free' => $request->months_free,
            'is_active' => $request->has('is_active'),
            'order' => PaymentPromo::max('order') + 1,
        ]);

        return response()->json(['success' => 'Promo pembayaran berhasil ditambahkan']);
    }

    public function show(PaymentPromo $paymentPromo)
    {
        $paymentPromo->load('category');
        return response()->json($paymentPromo);
    }

    public function update(Request $request, PaymentPromo $paymentPromo)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:100',
            'months_paid' => 'required|integer|min:1',
            'months_free' => 'required|integer|min:0',
        ]);

        $paymentPromo->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'months_paid' => $request->months_paid,
            'months_free' => $request->months_free,
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json(['success' => 'Promo pembayaran berhasil diperbarui']);
    }

    public function destroy(PaymentPromo $paymentPromo)
    {
        $paymentPromo->delete();
        return response()->json(['success' => 'Promo pembayaran berhasil dihapus']);
    }
}