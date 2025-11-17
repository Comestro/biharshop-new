<?php

namespace App\Livewire\Member;

use App\Models\BinaryTree;
use App\Models\Membership;
use App\Models\ReferralTree;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class MyWallet extends Component
{
    public $walletBalance = 0.00;
    public $memberId;
    public $isWalletLocked = false;
    public $lockedDaily = 0.00;
    public $availableBalance = 0.00;
    public $commissionHistory = [];
    public $referralComissionHistory = [];
    public $binaryComissionHistory = true;
    public $activeCommissionTab = 'binary';
    public $binaryCommissionTotal = 0.00;
    public $referralCommissionTotal = 0.00;
    public $dailyCommissionTotal = 0.00;
    public $dailyCommissionTx = [];
    public $binaryTx = [];
    public $kycComplete = false;
    public $transactions = [];
    public $withdrawals = [];
    public $withdrawAmount = null;
    public $withdrawPreview = null;
    public $totalEarnings = 0.00;

    public function mount()
    {
        $this->memberId = auth()->user()->membership->id;
        $this->kycComplete = auth()->user()->membership->isKycComplete();
        

        $this->calculateCommission($this->memberId);
        $this->generateReferralCommissions($this->memberId);
        $this->loadWallet();
        $this->loadReferralHistory();
        $this->loadCommissionData();
    }

    // binary wala
    private function calculateCommission($memberId)
    {
        $this->commissionHistory = [];
        $baseAmount = Auth::user()->membership->plan?->price ?? 3000;
        $wallet = 0.0;

        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();

        if (!$left && !$right) {
            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'No Pair',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => 'No left member',
                'right_member' => 'No right member',
                'detail' => 'No binary started yet.',
            ];
            return;
        }

        if (!$left || !$right) {
            $this->commissionHistory[] = [
                'level' => '-',
                'status' => 'Partial',
                'percentage' => '-',
                'commission' => 0,
                'left_member' => $this->getToken($left->member_id ?? null),
                'right_member' => $this->getToken($right->member_id ?? null),
                'detail' => "One side missing.",
            ];
            return;
        }

        [$leftDepth, $leftMembers] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth, $rightMembers] = $this->getDirectionalDepth($right->member_id, 'right');

        $leftTokens = array_map(fn($id) => $this->getToken($id), $leftMembers);
        $rightTokens = array_map(fn($id) => $this->getToken($id), $rightMembers);

        $leftCount = 1 + $this->countSubtree($left->member_id);
        $rightCount = 1 + $this->countSubtree($right->member_id);
        $todayCount = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $capRemaining = max(25 - $todayCount, 0);

        $initialExists = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->where('meta->level', 1)
            ->exists();

        if ((($leftCount >= 2 && $rightCount >= 1) || ($rightCount >= 2 && $leftCount >= 1)) && ! $initialExists) {
            $commission = ($baseAmount * 16) / 100;
            $status = 'Pending';
            if ($capRemaining > 0) {
                $side = ($leftCount >= 2 && $leftCount >= $rightCount) ? 'left' : 'right';
                WalletTransaction::create([
                    'membership_id' => $this->memberId,
                    'type' => 'binary_commission',
                    'amount' => $commission,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => 1,
                        'left_member' => $leftTokens[0] ?? 'N/A',
                        'right_member' => $rightTokens[0] ?? 'N/A',
                        'percentage' => 16,
                        'initial_side' => $side
                    ]
                ]);
                $capRemaining--;
                $status = 'Paid';
                $wallet += $commission;
            }
            $this->commissionHistory[] = [
                'level' => 1,
                'status' => $status,
                'percentage' => '16%',
                'commission' => $commission,
                'left_member' => $leftTokens[0] ?? 'N/A',
                'right_member' => $rightTokens[0] ?? 'N/A',
                'detail' => '2:1 structure achieved'
            ];
        }

        $initialTx = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->where('meta->level', 1)
            ->first();
        $paidMatches = WalletTransaction::where('membership_id', $this->memberId)
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
                    $commission = ($baseAmount * 12) / 100;
                    $status = 'Pending';
                    if ($capRemaining > 0) {
                        WalletTransaction::create([
                            'membership_id' => $this->memberId,
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
                        $status = 'Paid';
                        $wallet += $commission;
                    }
                    $this->commissionHistory[] = [
                        'level' => $i + 1,
                        'status' => $status,
                        'percentage' => '12%',
                        'commission' => $commission,
                        'left_member' => 'N/A',
                        'right_member' => 'N/A',
                        'detail' => 'Pair matched'
                    ];
                }
            }
        }

        // wallet lock logic
        if (max($leftDepth, $rightDepth) > 1) {
            $this->isWalletLocked = true;
        }

        $this->walletBalance = $wallet;
    }


    private function getDirectionalDepth($memberId, $direction)
    {
        $depth = 1;
        $current = $memberId;
        $chain = [$current];

        while (true) {
            $next = BinaryTree::where('parent_id', $current)
                ->where('position', $direction)
                ->first();

            if (!$next)
                break;

            $depth++;
            $current = $next->member_id;
            $chain[] = $current;
        }

        return [$depth, $chain];
    }


    private function getToken($memberId)
    {
        if (!$memberId)
            return 'N/A';
        return Membership::find($memberId)?->token ?? 'N/A';
    }

    private function countSubtree($memberId)
    {
        $count = 0;
        $children = \App\Models\BinaryTree::where('parent_id', $memberId)->get();
        foreach ($children as $child) {
            $count++;
            $count += $this->countSubtree($child->member_id);
        }
        return $count;
    }


    private function generateReferralCommissions($memberId)
    {
        $levels = [3, 2, 1, 1, 1];
        $earnings = WalletTransaction::where('membership_id', $memberId)
            ->whereIn('type', ['binary_commission', 'daily_commission'])
            ->where('status', 'confirmed')
            ->sum('amount');
        if ($earnings <= 0) {
            return;
        }

        $childToken = $this->getToken($memberId);

        $current = $memberId;
        for ($i = 0; $i < 5; $i++) {
            $parent = ReferralTree::where('member_id', $current)->first();
            if (!$parent || !$parent->parent_id) break;
            $parentId = $parent->parent_id;

            $percent = $levels[$i];
            $target = ($earnings * $percent) / 100;
            $existing = WalletTransaction::where('membership_id', $parentId)
                ->where('type', 'referral_commission')
                ->where('meta->child_id', $childToken)
                ->where('meta->level', $i + 1)
                ->sum('amount');
            $delta = max($target - $existing, 0);

            if ($delta > 0) {
                WalletTransaction::create([
                    'membership_id' => $parentId,
                    'type' => 'referral_commission',
                    'amount' => $delta,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => $i + 1,
                        'child_id' => $childToken,
                        'percentage' => $percent
                    ]
                ]);
            }

            $current = $parentId;
        }
    }

    private function loadWallet()
    {
        $credits = WalletTransaction::where('membership_id', $this->memberId)
            ->whereIn('type', ['binary_commission', 'referral_commission', 'daily_commission'])
            ->where('status', 'confirmed')
            ->sum('amount');
        $debits = Withdrawal::where('membership_id', $this->memberId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        $this->walletBalance = $credits - $debits;
        $pinCount = (Membership::find($this->memberId)?->used_pin_count ?? 0);
        $this->isWalletLocked = $this->hasFirstPair($this->memberId);
        $this->lockedDaily = $this->isWalletLocked
            ? WalletTransaction::where('membership_id', $this->memberId)
                ->where('type', 'daily_commission')
                ->where('status', 'confirmed')
                ->sum('amount')
            : 0.00;
        $this->availableBalance = $pinCount >= 2 ? max($this->walletBalance - $this->lockedDaily, 0) : 0.00;
        $this->transactions = WalletTransaction::where('membership_id', $this->memberId)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
        $this->withdrawals = Withdrawal::where('membership_id', $this->memberId)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get()
            ->toArray();
    }

    private function loadReferralHistory()
    {
        $this->referralComissionHistory = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'referral_commission')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($tx) {
                $meta = $tx->meta ?? [];
                return [
                    'level' => $meta['level'] ?? '-',
                    'child_id' => $meta['child_id'] ?? '-',
                    'percentage' => $meta['percentage'] ?? '-',
                    'commission' => $tx->amount,
                ];
            })
            ->toArray();
    }

    public function withdraw()
    {
        $this->validate([
            'withdrawAmount' => 'required|numeric|min:1'
        ]);
        if (!$this->kycComplete) {
            return;
        }
        $this->loadWallet();
        if ($this->withdrawAmount > $this->availableBalance) {
            return;
        }
        $gross = round($this->withdrawAmount, 2);
        $service = round($gross * 0.05, 2);
        $tds = round($gross * 0.02, 2);
        $net = max(round($gross - $service - $tds, 2), 0);

        Withdrawal::create([
            'membership_id' => $this->memberId,
            'amount' => $gross,
            'status' => 'pending',
            'details' => [
                'gross_amount' => $gross,
                'service_charge' => $service,
                'tds' => $tds,
                'net_amount' => $net
            ]
        ]);
        $this->withdrawAmount = null;
        $this->withdrawPreview = null;
        $this->loadWallet();
        $this->loadCommissionData();
    }

    private function hasFirstPair($memberId)
    {
        $left = \App\Models\BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = \App\Models\BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();
        if (!$left || !$right) {
            return false;
        }
        // dd($left, $right);
        [$leftDepth] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth] = $this->getDirectionalDepth($right->member_id, 'right');
        $maxDepth = max($leftDepth, $rightDepth);
        $minDepth = min($leftDepth, $rightDepth);
        return $maxDepth >= 2 && $minDepth >= 1;
    }

    private function loadCommissionData()
    {
        $this->binaryCommissionTotal = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->where('status', 'confirmed')
            ->sum('amount');

        $this->referralCommissionTotal = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'referral_commission')
            ->where('status', 'confirmed')
            ->sum('amount');

        $this->dailyCommissionTotal = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'daily_commission')
            ->where('status', 'confirmed')
            ->sum('amount');

        $this->binaryTx = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->toArray();

        $this->dailyCommissionTx = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'daily_commission')
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get()
            ->toArray();

        $this->totalEarnings = round(($this->binaryCommissionTotal + $this->referralCommissionTotal + $this->dailyCommissionTotal), 2);
    }

    public function updatedWithdrawAmount()
    {
        $amount = floatval($this->withdrawAmount ?? 0);
        if ($amount <= 0) {
            $this->withdrawPreview = null;
            return;
        }
        $gross = round($amount, 2);
        $service = round($gross * 0.05, 2);
        $tds = round($gross * 0.02, 2);
        $net = max(round($gross - $service - $tds, 2), 0);
        $this->withdrawPreview = [
            'gross_amount' => $gross,
            'service_charge' => $service,
            'tds' => $tds,
            'net_amount' => $net,
        ];
    }


    public function render()
    {
        return view('livewire.member.my-wallet');
    }
}
