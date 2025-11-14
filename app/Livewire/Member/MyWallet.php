<?php

namespace App\Livewire\Member;

use App\Models\BinaryTree;
use App\Models\Membership;
use App\Models\ReferralTree;
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

    public function mount()
    {
        $this->memberId = auth()->user()->membership->id;

        // Binary Commission 
        $this->calculateCommission($this->memberId);

        // Referral Commission
        $refResult = $this->calculateReferralCommission($this->memberId, 3000);

        $this->referralComissionHistory = $refResult['levels'];

        // Total referral commission add to wallet balance
        $this->walletBalance += $refResult['total'];
    }

    // binary wala
    private function calculateCommission($memberId)
    {
        $baseAmount = Auth::user()->membership->plan->price ?? 2999;
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

        // 16% first pair
        if ($leftDepth >= 1 || $rightDepth >= 1) {
            $commission = ($baseAmount * 16) / 100;
            $wallet += $commission;

            $this->commissionHistory[] = [
                'level' => 1,
                'status' => 'Paid',
                'percentage' => '16%',
                'commission' => $commission,
                'left_member' => $leftTokens[0] ?? 'N/A',
                'right_member' => $rightTokens[0] ?? 'N/A',
                'detail' => 'First binary pair.',
            ];
        }

        // 12% matched levels
        $pairs = min($leftDepth, $rightDepth);
        if ($pairs > 1) {
            for ($i = 2; $i <= $pairs; $i++) {
                $commission = ($baseAmount * 12) / 100;
                $wallet += $commission;

                $this->commissionHistory[] = [
                    'level' => $i,
                    'status' => 'Paid',
                    'percentage' => '12%',
                    'commission' => $commission,
                    'left_member' => $leftTokens[$i - 1] ?? 'N/A',
                    'right_member' => $rightTokens[$i - 1] ?? 'N/A',
                    'detail' => "Pair matched level $i",
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

        $commissions = [];
        $current = $memberId;
        $total = 0;

        for ($i = 0; $i < 5; $i++) {

            $parent = ReferralTree::where('member_id', $current)->first();

            if (!$parent || !$parent->parent_id)
                break;

            $parentId = $parent->parent_id;

            $percent = $levels[$i];
            $calc = ($amount * $percent) / 100;

            $commissions[] = [
                'level' => $i + 1,
                'upline_id' => $this->getToken($parentId),
                'percentage' => $percent,
                'commission' => $calc,
            ];

            $total += $calc;

            $current = $parentId;
        }

        return [
            'total' => $total,
            'levels' => $commissions,
        ];
    }


    public function render()
    {
        return view('livewire.member.my-wallet');
    }
}
