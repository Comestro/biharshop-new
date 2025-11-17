<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseDailyCommission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:daily-commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add 16 rupees daily to all users wallet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info("Daily 16 rupees credit started...");

        $members = Membership::where('isPaid', true)
            ->where('isVerified', true)
            ->get();

        $processedMembers = 0;
        $createdCredits = 0;
        foreach ($members as $member) {
            $start = $member->created_at->copy()->startOfDay();
            $end = now()->copy()->startOfDay();
            $eligibleDays = min(30, $start->diffInDays($end) + 1);

            for ($i = 0; $i < $eligibleDays; $i++) {
                $date = $start->copy()->addDays($i);
                $existsForDate = WalletTransaction::where('membership_id', $member->id)
                    ->where('type', 'daily_commission')
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();
                if ($existsForDate) {
                    continue;
                }
                $totalReceived = WalletTransaction::where('membership_id', $member->id)
                    ->where('type', 'daily_commission')
                    ->sum('amount');
                if ($totalReceived >= 480) {
                    break;
                }
                DB::transaction(function () use ($member, $date) {
                    WalletTransaction::create([
                        'membership_id' => $member->id,
                        'type' => 'daily_commission',
                        'amount' => 16,
                        'status' => 'confirmed',
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                });
                $createdCredits++;
            }
            $processedMembers++;
        }

        \Log::info("Daily 16 rupees commission done. Members: {$processedMembers}, Credits: {$createdCredits}");
        $this->info("Daily commission credited for {$processedMembers} members. Rows created: {$createdCredits}");
    }

}
