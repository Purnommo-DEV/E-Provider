<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('page.categories.index');
    }

    public function data()
    {
        $categories = Category::orderBy('order', 'asc')->get();

        return datatables()->of($categories)
            ->addIndexColumn()
            ->addColumn('status', function ($category) {
                return $category->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-error">Nonaktif</span>';
            })
            ->addColumn('payment_promo', function ($category) {
                return $category->has_payment_promo ? '<span class="badge badge-info">Ada Promo</span>' : '<span class="badge badge-ghost">Tidak Ada</span>';
            })
            ->addColumn('action', function ($category) {
                return '
                    <button onclick="editCategory(' . $category->id . ')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="deleteCategory(' . $category->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
                ';
            })
            ->rawColumns(['status', 'payment_promo', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'subtitle' => $request->subtitle,
            'has_payment_promo' => $request->has('has_payment_promo'),
            'is_active' => $request->has('is_active'),
            'order' => Category::max('order') + 1,
        ]);

        return response()->json(['success' => 'Kategori berhasil ditambahkan']);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'subtitle' => $request->subtitle,
            'has_payment_promo' => $request->has('has_payment_promo'),
            'is_active' => $request->has('is_active'),
        ]);

        return response()->json(['success' => 'Kategori berhasil diperbarui']);
    }

    public function destroy(Category $category)
    {
        // Optional: cek jika ada paket terkait, block delete
        if ($category->packages()->count() > 0) {
            return response()->json(['error' => 'Kategori tidak bisa dihapus karena ada paket terkait'], 422);
        }

        $category->delete();
        return response()->json(['success' => 'Kategori berhasil dihapus']);
    }
}