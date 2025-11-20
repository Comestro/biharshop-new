<?php

namespace App\Console\Commands;

use App\Models\BinaryTree;
use App\Models\EPin;
use App\Models\Membership;
use App\Models\WalletTransaction;
use Illuminate\Console\Command;

class RecomputeBinaryReferral extends Command
{
    protected $signature = 'wallet:recompute-binary-referral';
    protected $description = 'Recompute binary and referral commissions for all members';

    public function handle()
    {
        $members = Membership::whereNotNull('token')->get(['id','token']);
        $processed = 0;
        foreach ($members as $member) {
            $this->calculateBinaryForMember($member->id);
            $this->insertReferralForMember($member->id);
            $processed++;
        }
        $this->info("Recomputed commissions for {$processed} members");
    }

    private function calculateBinaryForMember($memberId)
    {
        $baseAmount = \App\Models\MembershipPlan::where('membership_id', $memberId)
            ->where('status','active')
            ->join('plans','membership_plans.plan_id','=','plans.id')
            ->sum('plans.price');
        if ($baseAmount <= 0) {
            $baseAmount = EPin::where('used_by_membership_id', $memberId)->value('plan_amount') ?? 3000;
        }

        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();

        if (!$left || !$right) {
            return;
        }

        $leftCount = 1 + $this->countSubtree($left->member_id);
        $rightCount = 1 + $this->countSubtree($right->member_id);

        $todayCount = WalletTransaction::where('membership_id', $memberId)
            ->where('type', 'binary_commission')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $capRemaining = max(25 - $todayCount, 0);

        $initialExists = WalletTransaction::where('membership_id', $memberId)
            ->where('type', 'binary_commission')
            ->where('meta->level', 1)
            ->exists();

        if (((($leftCount >= 2 && $rightCount >= 1) || ($rightCount >= 2 && $leftCount >= 1))) && !$initialExists) {
            $commission = ($baseAmount * 16) / 100;
            if ($capRemaining > 0) {
                $side = ($leftCount >= 2 && $leftCount >= $rightCount) ? 'left' : 'right';
                WalletTransaction::create([
                    'membership_id' => $memberId,
                    'type' => 'binary_commission',
                    'amount' => $commission,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => 1,
                        'left_member' => 'N/A',
                        'right_member' => 'N/A',
                        'percentage' => 16,
                        'initial_side' => $side
                    ]
                ]);
                $capRemaining--;
            }
        }

        $initialTx = WalletTransaction::where('membership_id', $memberId)
            ->where('type', 'binary_commission')
            ->where('meta->level', 1)
            ->first();
        $paidMatches = WalletTransaction::where('membership_id', $memberId)
            ->where('type', 'binary_commission')
            ->where('meta->level', '>=', 2)
            ->count();

        if ($initialTx) {
            $side = $initialTx->meta['initial_side'] ?? (($leftCount >= $rightCount) ? 'left' : 'right');
            $consLeft = $side === 'left' ? 2 : 1;
            $consRight = $side === 'left' ? 1 : 2;
            $availLeft = max($leftCount - $consLeft, 0);
            $availRight = max($rightCount - $consRight, 0);
            $targetMatches = min($availLeft, $availRight);

            if ($targetMatches > $paidMatches) {
                for ($i = $paidMatches + 1; $i <= $targetMatches; $i++) {
                    if ($capRemaining <= 0) { break; }
                    $commission = ($baseAmount * 12) / 100;
                    WalletTransaction::create([
                        'membership_id' => $memberId,
                        'type' => 'binary_commission',
                        'amount' => $commission,
                        'status' => 'confirmed',
                        'meta' => [
                            'level' => $i + 1,
                            'left_member' => 'N/A',
                            'right_member' => 'N/A',
                            'percentage' => 12
                        ]
                    ]);
                    $capRemaining--;
                }
            }
        }
    }

    private function insertReferralForMember($memberId)
    {
        $levels = [3, 2, 1, 1, 1];
        $childToken = Membership::find($memberId)?->token ?? null;
        if (!$childToken) return;
        $childWallet = WalletTransaction::where('membership_id', $memberId)
            ->whereIn('type', ['binary_commission'])
            ->where('status', 'confirmed')
            ->sum('amount');
        if ($childWallet <= 0) { return; }

        $current = $memberId;
        for ($i = 0; $i < 5; $i++) {
            $parent = BinaryTree::where('member_id', $current)->first();
            if (!$parent || !$parent->parent_id) break;
            $uplineId = $parent->parent_id;
            $percent = $levels[$i];
            $amount = ($childWallet * $percent) / 100;

            WalletTransaction::updateOrCreate(
                [
                    'membership_id' => $uplineId,
                    'type' => 'referral_commission',
                    'meta->child_id' => $childToken,
                    'meta->level' => $i + 1,
                ],
                [
                    'amount' => $amount,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => $i + 1,
                        'child_id' => $childToken,
                        'percentage' => $percent,
                        'base_wallet' => $childWallet
                    ]
                ]
            );

            $current = $uplineId;
        }
    }

    private function countSubtree($memberId)
    {
        $count = 0;
        $children = BinaryTree::where('parent_id', $memberId)->get();
        foreach ($children as $child) {
            $count++;
            $count += $this->countSubtree($child->member_id);
        }
        return $count;
    }
}

