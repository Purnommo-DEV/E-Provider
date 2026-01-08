<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StreamingAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StreamingAddonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('page.streaming-addons.index');
    }

    /**
     * Provide data for DataTables.
     */
    public function data()
    {
        $query = StreamingAddon::query();

        return datatables($query)
            ->addColumn('action', function ($addon) {
                return '
                    <button onclick="editStreamingAddon(' . $addon->id . ')" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></button>
                    <button onclick="deleteStreamingAddon(' . $addon->id . ')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|in:0,1',  // hanya izinkan 0 atau 1
        ]);

        // Convert ke boolean jika mau disimpan sebagai boolean di DB
        $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === true;
        $validated['key'] = Str::slug($validated['name']);

        $addon = StreamingAddon::create($validated);

        return response()->json(['success' => 'Add-on created successfully', 'data' => $addon], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StreamingAddon $streamingAddon)
    {
        return response()->json($streamingAddon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StreamingAddon $streamingAddon)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|in:0,1',  // hanya izinkan 0 atau 1
        ]);

        // Convert ke boolean jika mau disimpan sebagai boolean di DB
        $validated['is_active'] = $validated['is_active'] === '1' || $validated['is_active'] === true;
        $validated['key'] = Str::slug($validated['name']);

        $streamingAddon->update($validated);

        return response()->json(['success' => 'Add-on updated successfully', 'data' => $streamingAddon]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StreamingAddon $streamingAddon)
    {
        $streamingAddon->delete();

        return response()->json(['success' => 'Add-on deleted successfully']);
    }

    /**
     * List for dropdown (API for form paket)
     */
    public function listForDropdown()
    {
        $addons = StreamingAddon::select('id', 'name', 'key', 'color')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($addons);
    }
}