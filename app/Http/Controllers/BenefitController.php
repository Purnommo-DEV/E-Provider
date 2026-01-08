<?php

namespace App\Http\Controllers;

use App\Models\Benefit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BenefitController extends Controller
{
    public function index()
    {
        return view('page.benefits.index');
    }

    public function data()
    {
        $items = Benefit::latest()->get();

        return datatables()->of($items)
            ->addColumn('logo_preview', function ($row) {
                if (!$row->logo_url) return '-';

                return '<img src="'.asset('storage/'.$row->logo_url).'"
                        class="w-12 h-12 object-contain mx-auto">';
            })
            ->addColumn('category_label', function ($row) {
                return strtoupper($row->category);
            })
            ->addColumn('action', function ($row) {
                return '
                    <div class="flex justify-center gap-2">
                        <button onclick="editBenefit('.$row->id.')"
                            class="btn btn-sm btn-warning text-white">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteBenefit('.$row->id.')"
                            class="btn btn-sm btn-error">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['logo_preview', 'action'])
            ->make(true);
    }

    public function listForDropdown()
    {
        $benefits = Benefit::select('id', 'name', 'category')
            ->orderBy('name')
            ->get();

        return response()->json($benefits);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:ott,bonus',
            'logo' => 'nullable|image|mimes:png,jpg,svg|max:2048'
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('benefits', 'public');
        }

        Benefit::create([
            'name' => $request->name,
            'category' => $request->category,
            'logo_url' => $logoPath
        ]);

        return response()->json(['success' => 'Benefit berhasil ditambahkan']);
    }

    public function show(Benefit $benefit)
    {
        return response()->json($benefit);
    }

    public function update(Request $request, Benefit $benefit)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:ott,bonus',
            'logo' => 'nullable|image|mimes:png,jpg,svg|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            if ($benefit->logo_url) {
                Storage::disk('public')->delete($benefit->logo_url);
            }
            $benefit->logo_url = $request->file('logo')->store('benefits', 'public');
        }

        $benefit->update([
            'name' => $request->name,
            'category' => $request->category
        ]);

        return response()->json(['success' => 'Benefit berhasil diperbarui']);
    }

    public function destroy(Benefit $benefit)
    {
        if ($benefit->logo_url) {
            Storage::disk('public')->delete($benefit->logo_url);
        }

        $benefit->delete();

        return response()->json(['success' => 'Benefit berhasil dihapus']);
    }
}
