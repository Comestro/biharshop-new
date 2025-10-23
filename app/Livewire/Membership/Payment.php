<?php

namespace App\Livewire\Membership;

use Livewire\Component;
use App\Models\Membership;

class Payment extends Component
{
    public $membership;
    public $transaction_no;
    public $amount = 2999; // Set your membership amount

    public function mount(Membership $membership)
    {
        $this->membership = $membership;

        if ($membership->isPaid) {
            return redirect()->route('member.dashboard');
        }
    }

    public function submitPayment()
    {
        $this->validate([
            'transaction_no' => 'required|string|min:6'
        ]);

        $this->membership->update([
            'transaction_no' => $this->transaction_no,
            'isPaid' => true,
            'payment_status' => 'pending_verification'
        ]);

        session()->flash('message', 'Payment submitted successfully! Your membership will be activated after verification.');
        return redirect()->route('member.dashboard');  // Changed from membership.dashboard to member.dashboard
    }

    public function render()
    {
        return view('livewire.membership.payment');
    }
}
