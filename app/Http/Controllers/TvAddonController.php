<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TvAddon;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TvAddonController extends Controller
{
    public function index()
    {
        $tvAddon = TvAddon::firstOrCreate([]); // single record
        $channels = Channel::where('is_active', true)->orderBy('order')->get();

        return view('page.tv-addon.index', compact('tvAddon', 'channels'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'price_text' => 'nullable|string|max:255',
            'channel_count' => 'required|integer|min:0',
            'device_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $tvAddon = TvAddon::firstOrCreate([]);

        $data = $request->only([
            'title', 'subtitle', 'description', 'price', 'price_text', 'channel_count'
        ]);

        if ($request->hasFile('device_image')) {
            if ($tvAddon->device_image) Storage::disk('public')->delete($tvAddon->device_image);
            $data['device_image'] = $request->file('device_image')->store('tv_addons', 'public');
        }

        $tvAddon->update($data);

        return response()->json(['success' => 'Pengaturan Hiburan Terlengkap berhasil diperbarui']);
    }
}