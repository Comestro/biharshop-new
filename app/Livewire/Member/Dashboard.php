<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Membership;

class Dashboard extends Component
{
    public $membership;
    public $directReferrals;
    public $treeMembers;

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
        
        if ($this->membership->binaryPosition) {
            // Count all members in the binary tree under this member
            $this->treeMembers = $this->countTreeMembers($this->membership->id);
        }
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
