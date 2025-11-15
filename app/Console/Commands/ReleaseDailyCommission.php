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

        $members = Membership::where('payment_status', 'success')->get();

        foreach ($members as $member) {


            $totalReceived = WalletTransaction::where('membership_id', $member->id)
                ->where('type', 'daily_commission')
                ->sum('amount');

            if ($totalReceived >= 480) {
                continue; // return nahi
            }

            DB::transaction(function () use ($member) {
                WalletTransaction::create([
                    'membership_id' => $member->id,
                    'type' => 'daily_commission',
                    'amount' => 16,
                    'status' => 1, // confirmed
                ]);
            });
        }

        \Log::info("Daily 16 rupees commission done.");
    }

}
