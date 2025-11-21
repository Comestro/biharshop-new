<?php

namespace App\Livewire\Member;

use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.member')]
class KycDocuments extends Component
{
    use WithFileUploads;

    public $pancard_image;
    public $aadhar_card_image;
    public $status;
    public $rejection_reason;

    public function mount()
    {
        $m = Auth::user()->membership;
        $this->status = $m->kyc_status ?? 'pending';
        $this->rejection_reason = $m->kyc_rejection_reason ?? null;
    }

    public function save()
    {
        $this->validate([
            'pancard_image' => 'required|image|max:2048',
            'aadhar_card_image' => 'required|image|max:4096',
        ]);

        $member = Auth::user()->membership;
        $panPath = $this->pancard_image->store('kyc', 'public');
        $aadharPath = $this->aadhar_card_image->store('kyc', 'public');

        $member->pancard_image = $panPath;
        $member->aadhar_card_image = $aadharPath;
        $member->kyc_status = 'pending';
        $member->kyc_rejection_reason = null;
        $member->save();

        $this->status = 'pending';
        $this->rejection_reason = null;
        session()->flash('message', 'Documents uploaded. Awaiting verification.');
    }

    public function render()
    {
        return view('livewire.member.kyc-documents', [
            'membership' => Auth::user()->membership
        ]);
    }
}

