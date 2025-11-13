<?php

namespace App\Livewire\Member\Component;

use App\Models\BinaryTree;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TotalWalletBalance extends Component
{

    public $walletBalance = 0.00;
    public $memberId;
    public $isWalletLocked = false;
    public $commissionHistory = [];

    public function mount()
    {
        $this->memberId = 9;
        $this->calculateCommission($this->memberId);
    }

    private function calculateCommission($memberId)
    {
        $baseAmount = Auth::user()->membership->plan->price ?? 2999;
        $wallet = 0.0;

        $left = BinaryTree::where('parent_id', $memberId)->where('position', 'left')->first();
        $right = BinaryTree::where('parent_id', $memberId)->where('position', 'right')->first();


        [$leftDepth, $leftMembers] = $this->getDirectionalDepth($left->member_id, 'left');
        [$rightDepth, $rightMembers] = $this->getDirectionalDepth($right->member_id, 'right');

        // ✅ 16% commission
        if ($leftDepth >= 1 || $rightDepth >= 1) {
            $commission = ($baseAmount * 16) / 100;
            $wallet += $commission;
        }

        // ✅ 12% for matched levels
        $pairs = min($leftDepth, $rightDepth);
        if ($pairs > 1) {
            for ($i = 2; $i <= $pairs; $i++) {
                $commission = ($baseAmount * 12) / 100;
                $wallet += $commission;

            }
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

    public function render()
    {
        return view('livewire.member.component.total-wallet-balance');
    }
}
