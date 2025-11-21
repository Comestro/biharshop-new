<?php

namespace App\Console\Commands;

use App\Models\Membership;
use App\Models\WalletTransaction;
use App\Models\EPin;
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
    protected $description = 'Add ₹16 daily cashback for 3000-plan members, up to 30 days (₹480 max)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info("Daily 16 rupees credit started...");

        $members = Membership::whereNotNull('token')->get();
        $processedMembers = 0;
        $createdCredits = 0;
        foreach ($members as $member) {
            $hasPlan3000 = \App\Models\MembershipPlan::where('membership_id', $member->id)
                ->where('status','active')
                ->join('plans','membership_plans.plan_id','=','plans.id')
                ->where('plans.price', 3000)
                ->exists();


            $pinAmount = EPin::where('used_by_membership_id', $member->id)->value('plan_amount');
            $qualifies = $hasPlan3000 || ($pinAmount == 3000);

            if (! $qualifies) {
                continue;
            }

            $perDay = 16.00;
            $capTotal = 480.00;
            $start = $member->created_at->copy()->startOfDay();
            $end = now()->copy()->startOfDay();
            $eligibleDays = min(30, $start->diffInDays($end) + 1);

            for ($i = 0; $i < $eligibleDays; $i++) {
                $date = $start->copy()->addDays($i);
                $existsForDate = WalletTransaction::where('membership_id', $member->id)
                    ->where('type', 'daily_cashback')
                    ->whereDate('created_at', $date->toDateString())
                    ->exists();
                if ($existsForDate) {
                    continue;
                }
                $totalReceived = WalletTransaction::where('membership_id', $member->id)
                    ->where('type', 'daily_cashback')
                    ->sum('amount');
                if ($totalReceived >= $capTotal) {
                    break;
                }
                DB::transaction(function () use ($member, $date, $perDay) {
                    WalletTransaction::create([
                        'membership_id' => $member->id,
                        'type' => 'daily_cashback',
                        'amount' => $perDay,
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
