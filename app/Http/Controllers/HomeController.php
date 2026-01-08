<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Channel;
use App\Models\Package;
use App\Models\Term;
use App\Models\PaymentPromo;
use App\Models\PackageType;
use App\Models\StreamingAddon;
use App\Models\PackageStreamingAddon;
use App\Models\TvAddon;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)->get();
        $packages = Package::with([
            'category',
            'packageType',
            'paymentPromo',
            'features' => fn($q) => $q->orderBy('sort_order'),
            'benefits' => fn($q) => $q->select('benefits.id', 'name', 'category')->withPivot('duration_value', 'duration_unit'),
            'streamingAddons' => fn($q) => $q->select('streaming_addons.id', 'name')
        ])
        ->where('is_active', true)
        ->orderBy('base_price')
        ->get();
        
        $allPackages = $packages->map(function ($package) {
            return [
                'id' => $package->id,
                'name' => $package->name ?? 'Nama Paket',
                'base_price' => $package->base_price ?? 0,
                'speed_mbps' => $package->speed_mbps ?? 0,
                'speed_up_to_mbps' => $package->speed_up_to_mbps ?? null,
                'promo_label' => $package->promo_label ?? null,
                'has_tv' => $package->has_tv ?? false,
                'channel_count' => $package->channel_count ?? null,
                'stb_info' => $package->stb_info ?? null,
                'category_id' => $package->category_id,
                'package_type_id' => $package->package_type_id,
                'streaming_addons' => $package->relationLoaded('streamingAddons')
                                        ? $package->streamingAddons->pluck('name')->toArray()
                                        : [],
                'benefits' => $package->relationLoaded('benefits')
                                        ? $package->benefits->map(fn($b) => trim($b->name . ' ' . ($b->pivot->duration_value ?? '') . ' ' . ($b->pivot->duration_unit ?? '')))->toArray()
                                        : [],
                'features' => $package->relationLoaded('features')
                                        ? $package->features->map(fn($f) => ['label' => $f->label, 'icon' => $f->icon ?? 'check'])->toArray()
                                        : [],
                'image' => $package->image ? asset('storage/' . $package->image) : asset('images/default-package-header.jpg'),
            ];
        });
        $banners = Banner::where('is_active', true)->get();
        $features = Feature::where('is_active', true)->get();
        $channels = Channel::where('is_active', true)->get();
        $term = Term::where('is_active', true)->first();
        $tv_addon = TvAddon::first();
        // Tambahan: Data promo & tipe paket per kategori (untuk JS filter dinamis)
        $promosByCategory = $categories->mapWithKeys(function ($cat) {
            return [$cat->id => PaymentPromo::where('category_id', $cat->id)->get(['id', 'name'])];
        });
        // atau lebih ringkas
        $typesByPromo = PackageType::whereNotNull('payment_promo_id')
            ->get(['id', 'payment_promo_id'])
            ->groupBy('payment_promo_id')
            ->map->pluck('id')
            ->toArray();
       
        $typesByCategory = $categories->mapWithKeys(function ($cat) {
            return [$cat->id => PackageType::where('category_id', $cat->id)
                ->get(['id', 'name', 'supports_streaming_addons']) // ← TAMBAHKAN INI
                ->map(function ($type) {
                    return [
                        'id' => $type->id,
                        'name' => $type->name,
                        'supports_streaming_addons' => (int) $type->supports_streaming_addons, // paksa jadi integer
                    ];
                })];
        });
        $streamingAddons = StreamingAddon::select('id', 'name', 'icon' /* opsional */)
            ->where('is_active', true) // jika ada flag aktif
            ->orderBy('name')
            ->get();
        $addonsByPackage = PackageStreamingAddon::query()
            ->select('package_id', 'streaming_addon_id')
            ->get()
            ->groupBy('package_id')
            ->map(fn($group) => $group->pluck('streaming_addon_id')->toArray())
            ->toArray();
        return view('welcome', compact(
            'categories',
            'packages',
            'banners',
            'features',
            'channels',
            'term',
            'tv_addon',
            'promosByCategory',
            'typesByCategory',
            'typesByPromo',
            'streamingAddons',
            'addonsByPackage',
            'allPackages'
        ));
    }
}