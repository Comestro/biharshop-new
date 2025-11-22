<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Membership;
use App\Models\WalletTransaction;
use App\Models\Withdrawal;

class Dashboard extends Component
{
    public $membership;
    public $directReferrals;
    public $treeMembers;
    public $leftTeamSize;
    public $rightTeamSize;
    public $walletBalance = 0.0;

    public function mount()
    {
        $this->membership = auth()->user()->membership;

        if (!$this->membership) {
            return redirect()->route('membership.register');
        }

        $this->loadStats();
    }

    protected function loadStats()
    {
        $this->directReferrals = Membership::where('referal_id', $this->membership->id)->count();
        $this->treeMembers = 0; // Initialize to 0
        $this->leftTeamSize = 0;
        $this->rightTeamSize = 0;
        $credits = WalletTransaction::where('membership_id', $this->membership->id)
            ->whereIn('type', ['binary_commission', 'referral_commission', 'daily_cashback'])
            ->where('status', 'confirmed')
            ->sum('amount');
        $debits = Withdrawal::where('membership_id', $this->membership->id)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
        $this->walletBalance = $credits - $debits;

        // Get left and right legs first
        $leftChild = \App\Models\BinaryTree::where('parent_id', $this->membership->id)
            ->where('position', 'left')
            ->first();

        $rightChild = \App\Models\BinaryTree::where('parent_id', $this->membership->id)
            ->where('position', 'right')
            ->first();

        // Count members in left leg
        if ($leftChild) {
            $this->leftTeamSize = 1 + $this->countTreeMembers($leftChild->member_id);
        }

        // Count members in right leg
        if ($rightChild) {
            $this->rightTeamSize = 1 + $this->countTreeMembers($rightChild->member_id);
        }

        // Total tree members is the sum of both legs
        $this->treeMembers = $this->leftTeamSize + $this->rightTeamSize;
    }

    protected function countTreeMembers($memberId)
    {
        $count = 0;
        $children = \App\Models\BinaryTree::where('parent_id', $memberId)->get();

        foreach ($children as $child) {
            $count++; // Count this child
            $count += $this->countTreeMembers($child->member_id); // Add all descendants
        }

        return $count;
    }

    public function render()
    {
        return view('livewire.member.dashboard')
            ->layout('components.layouts.member');
    }
}
