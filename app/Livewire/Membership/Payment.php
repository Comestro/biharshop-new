<?php
namespace App\Livewire\Membership;

use Livewire\Component;
use App\Models\Membership;

class Payment extends Component
{
    public $membership;
    public $transaction_no;

    public function mount(Membership $membership)
    {
        $this->membership = $membership;
    }

    public function processPayment()
    {
        $this->validate([
            'transaction_no' => 'required'
        ]);

        $this->membership->update([
            'transaction_no' => $this->transaction_no,
            'isPaid' => true,
            'payment_status' => 'pending_verification'
        ]);

        session()->flash('message', 'Payment submitted for verification');
    }

    public function render()
    {
        return view('livewire.membership.payment');
    }
}
