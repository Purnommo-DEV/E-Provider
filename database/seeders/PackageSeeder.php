<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\Category;
use App\Models\PackageType;
use App\Models\PaymentPromo;
use App\Models\Benefit;
use App\Models\StreamingAddon;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->command->error('Tidak ada kategori. Buat kategori dulu!');
            return;
        }

        $benefits = Benefit::get();
        $streamingAddons = StreamingAddon::where('is_active', true)->get();

        foreach ($categories as $category) {
            $packageTypes = PackageType::where('category_id', $category->id)->get();
            if ($packageTypes->isEmpty()) {
                $this->command->warn("Kategori {$category->name} tidak punya tipe paket. Lewati.");
                continue;
            }

            $promos = PaymentPromo::where('category_id', $category->id)->get();

            for ($i = 1; $i <= 10; $i++) {
                $type = $packageTypes->random();
                $promo = $promos->random() ?? null;

                $speedBase = rand(50, 500);
                $speedUpTo = $speedBase + rand(50, 300);

                $package = Package::create([
                    'category_id' => $category->id,
                    'package_type_id' => $type->id,
                    'payment_promo_id' => $promo?->id,
                    'name' => $this->generatePackageName($category->name, $type->name, $speedBase),
                    'speed_mbps' => $speedBase,
                    'speed_up_to_mbps' => $speedUpTo,
                    'internet_type' => 'unlimited',
                    'billing_type' => rand(0,1) ? 'monthly' : 'multi_month',
                    'duration_month' => rand(1,12),
                    'base_price' => rand(200000, 1200000),
                    'tax_included' => rand(0,1),
                    'promo_label' => rand(0,1) ? 'Promo ' . rand(3,12) . ' bln GRATIS ' . rand(1,3) . ' bln' : null,
                    'has_tv' => rand(0,1),
                    'channel_count' => rand(0,1) ? rand(60,120) : null,
                    'stb_info' => rand(0,1) ? 'Termasuk STB Android 12' : null,
                    'is_default' => false,
                    'is_active' => true,
                ]);

                // Fitur standar
                $features = [
                    ['label' => 'Internet UNLIMITED', 'icon' => 'infinity', 'sort_order' => 1],
                    ['label' => 'Include ONT/Modem', 'icon' => 'router', 'sort_order' => 2],
                    ['label' => 'Gratis Instalasi Rp500.000', 'icon' => 'gift', 'sort_order' => 3],
                    ['label' => 'WiFi 6 Dual Band', 'icon' => 'wifi', 'sort_order' => 4],
                    ['label' => 'Support Gaming & Streaming 4K', 'icon' => 'gamepad', 'sort_order' => 5],
                ];
                foreach (array_slice($features, 0, rand(3,5)) as $index => $f) {
                    $package->features()->create($f);
                }

                // Benefit
                if ($benefits->isNotEmpty()) {
                    $randomCount = rand(1, min(4, $benefits->count()));
                    $selectedBenefits = $benefits->random($randomCount);
                    foreach ($selectedBenefits as $benefit) {
                        $package->benefits()->attach($benefit->id, [
                            'duration_value' => rand(3,12),
                            'duration_unit' => rand(0,1) ? 'BULAN' : 'PEMBAYARAN',
                        ]);
                    }
                }

                // Streaming Add-ons (diperbaiki)
                if ($streamingAddons->isNotEmpty()) {
                    $randomCount = rand(0, min(3, $streamingAddons->count()));
                    if ($randomCount > 0) {
                        $selectedIds = $streamingAddons->random($randomCount)->pluck('id')->toArray();
                        $package->streamingAddons()->sync($selectedIds);
                    }
                }

                $this->command->info("Paket {$package->name} berhasil dibuat untuk kategori {$category->name}");
            }
        }
    }

    private function generatePackageName($categoryName, $typeName, $speed)
    {
        $prefixes = ['Nexus', 'Velo', 'Hyper', 'Ultra', 'Pro', 'Elite', 'Max', 'Prime', 'Turbo', 'Giga'];
        $suffixes = ['Unlimited', 'Plus', 'Turbo', 'Pro', 'Max', 'Elite', 'Ultra'];

        return $prefixes[array_rand($prefixes)] . ' ' . $speed . '/' . ($speed + rand(50,300)) . ' ' . $suffixes[array_rand($suffixes)];
    }
}