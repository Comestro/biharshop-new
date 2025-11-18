<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\ReferralTree;
use App\Models\Membership;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.member')]

class Referrals extends Component
{
    public $level = 1;
    public $referrals = [];

    public function mount()
    {
        $this->loadReferrals();
    }

    public function updatedLevel()
    {
        $this->loadReferrals();
    }

    public function loadReferrals()
    {
        $userId = auth()->id();

        // BFS level-by-level traversal
        $current = [$userId];

        for ($i = 1; $i <= $this->level; $i++) {

            $next = ReferralTree::whereIn('parent_id', $current)
                ->pluck('member_id')
                ->toArray();

            // On exact level → return those members
            if ($i == $this->level) {
                $this->referrals = Membership::whereIn('id', $next)->get();
                return;
            }

            // Prepare next depth
            $current = $next;
        }

        $this->referrals = [];
    }

    public function render()
    {
        return view('livewire.member.referrals');
    }
}
