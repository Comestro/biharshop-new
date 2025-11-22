<?php

namespace App\Livewire\Member;

use App\Models\Membership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.member')]
class ResetEpinPassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $member = Membership::find(Auth::user()->membership->id);

        // Old password check using decrypt()
        if (decrypt($member->epin_password) !== $this->current_password) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        // Update EPIN password using encrypt()
        $member->update([
            'epin_password' => encrypt($this->new_password),
        ]);

        // Reset fields
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success', 'Password changed successfully!');
    }
    public function createPassword()
    {
        $this->validate([
            'new_password' => 'required|min:8|confirmed',
        ]);

        $member = Membership::find(Auth::user()->membership->id);

        // Update EPIN password using encrypt()
        $member->update([
            'epin_password' => encrypt($this->new_password),
        ]);

        // Reset fields
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);

        session()->flash('success', 'Password changed successfully!');
    }
    public function render()
    {
        return view('livewire.member.reset-epin-password');
    }
}
