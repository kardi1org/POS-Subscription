<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pricing;
use App\Mail\RenewalReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendRenewalReminders extends Command
{
    protected $signature = 'pricing:send-reminders';
    protected $description = 'Kirim email pengingat perpanjangan untuk pricing yang akan berakhir dalam 3 hari';

    public function handle()
    {
        $today = Carbon::today();
        $pricings = Pricing::where('status', 'Aktif')
            ->whereNotNull('end_date')
            ->get();

        $count = 0;

        foreach ($pricings as $pricing) {
            $daysRemaining = Carbon::now()->diffInDays($pricing->end_date, false);

            // Kirim email 3 hari sebelum masa aktif berakhir
            if ($daysRemaining <= 3 && $daysRemaining >= 0) {
                if (!$pricing->reminder_sent_at || $pricing->reminder_sent_at->lt($today)) {
                    try {
                        Mail::to($pricing->email)->send(new RenewalReminderMail($pricing, $daysRemaining));

                        $pricing->reminder_sent_at = now();
                        $pricing->save();

                        $count++;
                        $this->info("📧 Reminder terkirim ke {$pricing->email} ({$daysRemaining} hari tersisa)");
                    } catch (\Exception $e) {
                        $this->error("Gagal kirim email ke {$pricing->email}: " . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Selesai! Total {$count} email pengingat berhasil dikirim.");
        return Command::SUCCESS;
    }
}
