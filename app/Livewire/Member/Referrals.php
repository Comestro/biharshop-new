<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;

class Referrals extends Component
{
    use WithPagination;

    public function render()
    {
        // dd(auth()->user()->membership->id);
        $referrals = Membership::with('binaryPosition')
            ->where('referal_id', auth()->user()->membership->id)
            ->latest()
            ->paginate(10);

        return view('livewire.member.referrals', [
            'referrals' => $referrals
        ])->layout('components.layouts.member');
    }
}
