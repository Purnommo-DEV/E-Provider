<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ChannelController extends Controller
{
    public function index()
    {
        return view('page.channels.index');
    }

    public function data()
    {
        $channels = Channel::orderBy('order')->get();

        return datatables()->of($channels)
            ->addIndexColumn()
            ->addColumn('logo_preview', function ($channel) {
                return $channel->logo
                    ? '<img src="' . asset('storage/' . $channel->logo) . '" class="w-24 h-24 object-contain rounded-lg shadow">'
                    : '<span class="text-gray-400">No logo</span>';
            })
            ->addColumn('type', fn ($channel) => ucfirst($channel->type))
            ->addColumn('status', function ($channel) {
                return $channel->is_active
                    ? '<span class="badge badge-success">Aktif</span>'
                    : '<span class="badge badge-error">Nonaktif</span>';
            })
            ->addColumn('action', function ($channel) {
                return '
                    <button onclick="editChannel(' . $channel->id . ')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="deleteChannel(' . $channel->id . ')" class="btn btn-error btn-sm ml-2">Hapus</button>
                ';
            })
            ->rawColumns(['logo_preview', 'status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:tv,streaming',
        ]);

        $data = $request->only(['name', 'type']);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = (Channel::max('order') ?? 0) + 1;

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->storeWebp($request->file('logo'));
        }

        Channel::create($data);

        return response()->json(['success' => 'Channel berhasil ditambahkan']);
    }

    public function show(Channel $channel)
    {
        return response()->json($channel);
    }

    public function update(Request $request, Channel $channel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'type' => 'required|in:tv,streaming',
        ]);

        $data = $request->only(['name', 'type']);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            if ($channel->logo) {
                Storage::disk('public')->delete($channel->logo);
            }

            $data['logo'] = $this->storeWebp($request->file('logo'));
        }

        $channel->update($data);

        return response()->json(['success' => 'Channel berhasil diperbarui']);
    }

    public function destroy(Channel $channel)
    {
        if ($channel->logo) {
            Storage::disk('public')->delete($channel->logo);
        }

        $channel->delete();

        return response()->json(['success' => 'Channel berhasil dihapus']);
    }

    /**
     * Convert image to WEBP (Laravel 11 compatible)
     */
    private function storeWebp($file, $folder = 'channels')
    {
        $manager = new ImageManager(new Driver());

        $filename = Str::uuid() . '.webp';
        $path = storage_path("app/public/{$folder}");

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $manager
            ->read($file)
            ->toWebp(80) // kualitas ideal
            ->save($path . '/' . $filename);

        return "{$folder}/{$filename}";
    }
}
