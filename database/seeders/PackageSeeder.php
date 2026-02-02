<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Package;
use App\Models\Category;
use App\Models\PackageType;
use App\Models\PaymentPromo;
use App\Models\Benefit;
use App\Models\StreamingAddon;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $categories = Category::all();
            if ($categories->isEmpty()) {
                throw new \Exception('Seeder dihentikan: Tidak ada kategori.');
            }

            $benefits = Benefit::all();
            $streamingAddons = StreamingAddon::where('is_active', true)->get();

            foreach ($categories as $category) {

                $packageTypes = PackageType::where('category_id', $category->id)->get();
                if ($packageTypes->isEmpty()) {
                    throw new \Exception("Kategori '{$category->name}' tidak punya Package Type.");
                }

                $promos = PaymentPromo::where('category_id', $category->id)->get();

                for ($i = 1; $i <= 10; $i++) {

                    $type  = $packageTypes->random();
                    $promo = $promos->isNotEmpty() ? $promos->random() : null;

                    $speedBase = rand(50, 500);
                    $speedUpTo = $speedBase + rand(50, 300);

                    // =========================
                    // CREATE PACKAGE
                    // =========================
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
                        'promo_label' => rand(0,1)
                            ? 'Promo ' . rand(3,12) . ' bln GRATIS ' . rand(1,3) . ' bln'
                            : null,
                        'has_tv' => rand(0,1),
                        'channel_count' => rand(0,1) ? rand(60,120) : null,
                        'stb_info' => rand(0,1) ? 'Termasuk STB Android 12' : null,
                        'is_default' => false,
                        'is_active' => true,
                    ]);

                    if (!$package) {
                        throw new \Exception("Gagal membuat Package (Kategori: {$category->name})");
                    }

                    // =========================
                    // FEATURES
                    // =========================
                    $features = [
                        ['label' => 'Internet UNLIMITED', 'icon' => 'infinity', 'sort_order' => 1],
                        ['label' => 'Include ONT/Modem', 'icon' => 'router', 'sort_order' => 2],
                        ['label' => 'Gratis Instalasi Rp500.000', 'icon' => 'gift', 'sort_order' => 3],
                        ['label' => 'WiFi 6 Dual Band', 'icon' => 'wifi', 'sort_order' => 4],
                        ['label' => 'Support Gaming & Streaming 4K', 'icon' => 'gamepad', 'sort_order' => 5],
                    ];

                    foreach (array_slice($features, 0, rand(3,5)) as $feature) {
                        if (!$package->features()->create($feature)) {
                            throw new \Exception("Gagal create feature untuk package ID {$package->id}");
                        }
                    }

                    // =========================
                    // BENEFITS
                    // =========================
                    if ($benefits->isNotEmpty()) {
                        $selectedBenefits = $benefits->random(rand(1, min(4, $benefits->count())));
                        foreach ($selectedBenefits as $benefit) {
                            $package->benefits()->attach($benefit->id, [
                                'duration_value' => rand(3,12),
                                'duration_unit' => rand(0,1) ? 'BULAN' : 'PEMBAYARAN',
                            ]);
                        }
                    }

                    // =========================
                    // STREAMING ADDONS
                    // =========================
                    if ($streamingAddons->isNotEmpty()) {
                        $count = rand(0, min(3, $streamingAddons->count()));
                        if ($count > 0) {
                            $package->streamingAddons()->sync(
                                $streamingAddons->random($count)->pluck('id')->toArray()
                            );
                        }
                    }

                    $this->command->info("✔ Paket '{$package->name}' dibuat");
                }
            }

            DB::commit();
            $this->command->info('✅ PackageSeeder selesai TANPA error.');

        } catch (\Throwable $e) {

            DB::rollBack();

            $this->command->error('❌ SEEDER GAGAL TOTAL');
            $this->command->error('Pesan : ' . $e->getMessage());
            $this->command->error('File  : ' . $e->getFile());
            $this->command->error('Line  : ' . $e->getLine());
        }
    }

    private function generatePackageName($categoryName, $typeName, $speed)
    {
        $prefixes = ['Nexus', 'Velo', 'Hyper', 'Ultra', 'Pro', 'Elite', 'Max', 'Prime', 'Turbo', 'Giga'];
        $suffixes  = ['Unlimited', 'Plus', 'Turbo', 'Pro', 'Max', 'Elite', 'Ultra'];

        return $prefixes[array_rand($prefixes)]
            . ' ' . $speed . '/' . ($speed + rand(50,300))
            . ' ' . $suffixes[array_rand($suffixes)];
    }
}
