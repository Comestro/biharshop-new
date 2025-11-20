<?php

namespace App\Livewire\Member;

use App\Models\EPin;
use App\Models\Membership;
use App\Models\ReferralTree;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class EPins extends Component
{
    public $transferCode = '';
    public $transferToMemberId = '';
    public $transferMemberName = null;

    public $redeemCode = '';


    public function updatedTransferToMemberId($value)
    {
        if (!$value) {
            $this->transferMemberName = null;
            return;
        }

        $member = Membership::where('membership_id', $value)->first();

        $this->transferMemberName = $member ? $member->name : 'Not Found';
    }


    public function transfer()
    {
        $pin = EPin::where('code', $this->transferCode)->first();

        if (!$pin) {
            $this->dispatch('error', message: 'Invalid PIN Code');
            return;
        }

        if ($pin->status === 'used') {
            $this->dispatch('error', message: 'This PIN is already used.');
            return;
        }

        if ($pin->owner_user_id !== auth()->id()) {
            $this->dispatch('error', message: 'You do not own this PIN.');
            return;
        }

        $toMember = Membership::where('membership_id', $this->transferToMemberId)->first();

        if (!$toMember) {
            $this->dispatch('error', message: 'Recipient Member ID not found.');
            return;
        }

        if ($toMember->user_id == auth()->id()) {
            $this->dispatch('error', message: 'You cannot transfer PIN to yourself.');
            return;
        }

        $pin->update([
            'owner_user_id' => $toMember->user_id,
            'status' => 'available'
        ]);

        $this->dispatch('success', message: 'PIN transferred successfully!');

        $this->transferCode = '';
        $this->transferToMemberId = '';
        $this->transferMemberName = null;
    }


    public function render()
    {
        $pins = EPin::where('owner_user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.member.epins', [
            'pins' => $pins
        ]);
    }
}
