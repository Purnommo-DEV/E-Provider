<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    public function index()
    {
        return view('page.features.index');
    }

    public function data()
    {
        $features = Feature::orderBy('order', 'asc')->get();

        return datatables()->of($features)
            ->addIndexColumn()
            ->addColumn('icon_preview', function ($feature) {
                if ($feature->icon) {
                    return '<i class="heroicons ' . $feature->icon . ' w-10 h-10 text-purple-600"></i>';
                }
                return '<span class="text-gray-400">No icon</span>';
            })
            ->addColumn('status', function ($feature) {
                return $feature->is_active
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-error">Nonaktif</span>';
            })
            ->addColumn('action', function ($feature) {
                return '
                    <button onclick="editFeature(' . $feature->id . ')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="deleteFeature(' . $feature->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
                ';
            })
            ->rawColumns(['icon_preview', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'nullable|string|max:100',
        ]);

        Feature::create([
            'title'       => $request->title,
            'description' => $request->description,
            'icon'        => $request->icon,
            'is_active'   => $request->has('is_active'),
            'order'       => Feature::max('order') + 1,
        ]);

        return response()->json(['success' => 'Keunggulan berhasil ditambahkan']);
    }

    public function show(Feature $feature)
    {
        return response()->json($feature);
    }

    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon'        => 'nullable|string|max:100',
        ]);

        $feature->update([
            'title'       => $request->title,
            'description' => $request->description,
            'icon'        => $request->icon,
            'order'       => $request->order,
            'is_active'   => $request->has('is_active'),
        ]);

        return response()->json(['success' => 'Keunggulan berhasil diperbarui']);
    }

    public function destroy(Feature $feature)
    {
        $feature->delete();
        return response()->json(['success' => 'Keunggulan berhasil dihapus']);
    }
}