<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pricing;
use App\Models\Renewal;
use App\Models\Package;
use Carbon\Carbon;

class UpdatePricingPackage extends Command
{
    protected $signature = 'pricing:update-package';
    protected $description = 'Update codepaket & namapaket di tabel pricing dari renewal aktif (sekali saja per renewal)';

    public function handle()
    {
        $now = Carbon::now();

        $renewals = Renewal::where('status', 'aktif')
            ->whereDate('old_end_date', '<', $now)
            ->whereDate('new_end_date', '>', $now)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('pricing_id');

        $count = 0;

        foreach ($renewals as $pricingId => $group) {
            $latestRenewal = $group->first(); // ambil yang terbaru
            $package = Package::find($latestRenewal->new_package);

            if ($package) {
                $pricing = Pricing::find($pricingId);

                // pastikan pricing belum diset dengan package ini
                if ($pricing && $pricing->codepaket != $package->id) {
                    $pricing->codepaket = $package->id;
                    $pricing->namapaket = $package->name;
                    $pricing->save();

                    $count++;
                    $this->info("✅ Pricing ID {$pricing->id} diperbarui ke paket {$package->name}");
                }
            }
        }

        $this->info("Selesai! Total {$count} pricing berhasil diperbarui.");
        return Command::SUCCESS;
    }
}
