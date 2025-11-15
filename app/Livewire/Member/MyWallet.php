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
    public $commissionHistory = [];
    public $referralComissionHistory = [];
    public $binaryComissionHistory = true;
    public $kycComplete = false;
    public $isVerified = false;
    public $transactions = [];
    public $withdrawals = [];
    public $withdrawAmount = null;

    public function mount()
    {
        $this->memberId = auth()->user()->membership->id;
        $this->kycComplete = auth()->user()->membership->isKycComplete();
        $this->isVerified = auth()->user()->membership->isVerified;

        $this->calculateCommission($this->memberId);
        $this->generateReferralCommissions($this->memberId);
        $this->loadWallet();
        $this->loadReferralHistory();
    }

    // binary wala
    private function calculateCommission($memberId)
    {
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
                'left_member' => '❌ No left member',
                'right_member' => '❌ No right member',
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

        $maxDepth = max($leftDepth, $rightDepth);
        $minDepth = min($leftDepth, $rightDepth);
        $todayCount = WalletTransaction::where('membership_id', $this->memberId)
            ->where('type', 'binary_commission')
            ->whereDate('created_at', now()->toDateString())
            ->count();
        $capRemaining = max(25 - $todayCount, 0);

        if ($maxDepth >= 2 && $minDepth >= 1) {
            $commission = ($baseAmount * 16) / 100;
            $recorded = WalletTransaction::where('membership_id', $this->memberId)
                ->where('type', 'binary_commission')
                ->where('meta->level', 1)
                ->exists();
            $status = 'Pending';
            if (!$recorded && $capRemaining > 0) {
                WalletTransaction::create([
                    'membership_id' => $this->memberId,
                    'type' => 'binary_commission',
                    'amount' => $commission,
                    'status' => 'confirmed',
                    'meta' => [
                        'level' => 1,
                        'left_member' => $leftTokens[0] ?? 'N/A',
                        'right_member' => $rightTokens[0] ?? 'N/A',
                        'percentage' => 16
                    ]
                ]);
                $capRemaining--;
                $status = 'Paid';
                $wallet += $commission;
            } elseif ($recorded) {
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

        // 12% matched levels
        $pairs = $minDepth;
        if ($pairs > 1) {
            for ($i = 2; $i <= $pairs; $i++) {
                $commission = ($baseAmount * 12) / 100;
                $recorded = WalletTransaction::where('membership_id', $this->memberId)
                    ->where('type', 'binary_commission')
                    ->where('meta->level', $i)
                    ->exists();
                $status = 'Pending';
                if (!$recorded && $capRemaining > 0) {
                    WalletTransaction::create([
                        'membership_id' => $this->memberId,
                        'type' => 'binary_commission',
                        'amount' => $commission,
                        'status' => 'confirmed',
                        'meta' => [
                            'level' => $i,
                            'left_member' => $leftTokens[$i - 1] ?? 'N/A',
                            'right_member' => $rightTokens[$i - 1] ?? 'N/A',
                            'percentage' => 12
                        ]
                    ]);
                    $capRemaining--;
                    $status = 'Paid';
                    $wallet += $commission;
                } elseif ($recorded) {
                    $status = 'Paid';
                    $wallet += $commission;
                }
                $this->commissionHistory[] = [
                    'level' => $i,
                    'status' => $status,
                    'percentage' => '12%',
                    'commission' => $commission,
                    'left_member' => $leftTokens[$i - 1] ?? 'N/A',
                    'right_member' => $rightTokens[$i - 1] ?? 'N/A',
                    'detail' => 'Pair matched'
                ];
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



    // referral wala
    private function calculateReferralCommission($memberId, $amount = 3000)
    {
        $levels = [3, 2, 1, 1, 1];

        $base = Membership::find($memberId)?->plan?->price ?? $amount;

        $commissions = [];
        $current = $memberId;
        $total = 0;

        for ($i = 0; $i < 5; $i++) {

            $parent = ReferralTree::where('member_id', $current)->first();

            if (!$parent || !$parent->parent_id)
                break;

            $parentId = $parent->parent_id;

            $percent = $levels[$i];
            $calc = ($base * $percent) / 100;

            $commissions[] = [
                'level' => $i + 1,
                'upline_id' => $this->getToken($parentId),
                'percentage' => $percent,
                'commission' => $calc,
            ];
            // Display-only; actual referral commission distribution occurs when a member is verified

            $total += $calc;

            $current = $parentId;
        }

        return [
            'total' => $total,
            'levels' => $commissions,
        ];
    }

    private function generateReferralCommissions($memberId)
    {
        $levels = [3, 2, 1, 1, 1];
        $base = Membership::find($memberId)?->plan?->price ?? 3000;
        $childToken = $this->getToken($memberId);

        $current = $memberId;
        for ($i = 0; $i < 5; $i++) {
            $parent = ReferralTree::where('member_id', $current)->first();
            if (!$parent || !$parent->parent_id) break;
            $parentId = $parent->parent_id;

            $percent = $levels[$i];
            $amount = ($base * $percent) / 100;

            $exists = WalletTransaction::where('membership_id', $parentId)
                ->where('type', 'referral_commission')
                ->where('meta->child_id', $childToken)
                ->where('meta->level', $i + 1)
                ->exists();

            if (!$exists) {
                WalletTransaction::create([
                    'membership_id' => $parentId,
                    'type' => 'referral_commission',
                    'amount' => $amount,
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
            ->whereIn('type', ['binary_commission', 'referral_commission'])
            ->where('status', 'confirmed')
            ->sum('amount');
        $debits = Withdrawal::where('membership_id', $this->memberId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        $this->walletBalance = $credits - $debits;
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
        if ($this->withdrawAmount > $this->walletBalance) {
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
        $this->loadWallet();
    }


    public function render()
    {
        return view('livewire.member.my-wallet');
    }
}
