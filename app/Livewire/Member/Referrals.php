<?php

namespace App\Livewire\Member;

use App\Models\ReferralTree;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
#[Layout('components.layouts.member')]
class Referrals extends Component
{
    use WithPagination;
    public $membershipId = null;

    public function mount()
    {
        $this->membershipId = auth()->user()->membership->id;
    }
    public function nextLevel($id)
    {
        $this->membershipId = $id;
    }
    public function backLevel()
    {
        $membershipId = ReferralTree::where('member_id', $this->membershipId)->first();
        $backMembershipId = ReferralTree::where('member_id', $membershipId->parent_id)->first();

        $this->membershipId = $backMembershipId->parent_id ?? auth()->user()->membership->id;
    }
    public function render()
    {

        // dd($this->membershipId);
        $referrals = ReferralTree::where('parent_id', $this->membershipId)
            ->latest()
            ->paginate(10);

        return view('livewire.member.referrals', [
            'referrals' => $referrals
        ]);
    }
}
