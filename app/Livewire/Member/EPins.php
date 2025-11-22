<?php

namespace App\Livewire\Member;

use App\Models\EPin;
use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class EPins extends Component
{
    public $transferCode = '';
    public $transferToMemberId = '';
    public $transferMemberName = null;

    public $countSendingToken = 0;
    public $bulkTransferToMemberId = '';
    public $bulkTransferMemberName = null;

    public $search = '';
    public $statusFilter = '';

    
    public function updatedTransferToMemberId($value)
    {
        if (!$value) {
            $this->transferMemberName = null;
            return;
        }

        $member = Membership::where('membership_id', $value)->first();
        $this->transferMemberName = $member ? $member->name : 'Not Found';
    }

    
    public function updatedBulkTransferToMemberId($value)
    {
        if (!$value) {
            $this->bulkTransferMemberName = null;
            return;
        }

        $member = Membership::where('membership_id', $value)->first();
        $this->bulkTransferMemberName = $member ? $member->name : 'Not Found';
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
        ]);

        $this->dispatch('success', message: 'PIN transferred successfully!');

        $this->transferCode = '';
        $this->transferToMemberId = '';
        $this->transferMemberName = null;
    }

  
    public function sendBulkPin()
    {
        $this->validate([
            'countSendingToken' => 'required|integer|min:1',
            'bulkTransferToMemberId' => 'required',
        ]);

        $toMember = Membership::where('membership_id', $this->bulkTransferToMemberId)->first();

        if (!$toMember) {
            $this->dispatch('error', message: 'Recipient Member ID not found.');
            return;
        }

        if ($toMember->user_id == Auth::user()->id) {
            $this->dispatch('error', message: 'You cannot transfer PINs to yourself.');
            return;
        }

        // AVAILABLE PINS
        $availablePins = EPin::where('owner_user_id', Auth::id())
            ->whereNot('status', 'used')
            ->take($this->countSendingToken)
            ->get();

        if ($availablePins->count() < $this->countSendingToken) {
            $this->dispatch('error', message: 'Not enough available PINs.');
            return;
        }

        foreach ($availablePins as $pin) {
            $pin->update([
                'owner_user_id' => $toMember->user_id,
            ]);
        }

        $this->dispatch('success', message: "{$this->countSendingToken} PINs transferred successfully!");

        // RESET
        $this->countSendingToken = '';
        $this->bulkTransferToMemberId = '';
        $this->bulkTransferMemberName = null;
    }

    public function render()
    {
        $pins = EPin::where('owner_user_id', auth()->id())
            ->when(
                $this->search,
                fn($q) =>
                $q->where('code', 'LIKE', "%{$this->search}%")
            )
            ->when(
                $this->statusFilter,
                fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.member.epins', ['pins' => $pins]);
    }
}
