<?php

namespace App\Livewire\Admin;

use App\Models\Membership;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class KycVerification extends Component
{
    public $search = '';
    public $filter = 'pending';

    public function approve($id)
    {
        $m = Membership::findOrFail($id);
        $m->kyc_status = 'approved';
        $m->kyc_verified_at = now();
        $m->kyc_rejection_reason = null;
        $m->save();
        session()->flash('message', 'KYC approved');
    }

    public function reject($id, $reason = null)
    {
        $m = Membership::findOrFail($id);
        $m->kyc_status = 'rejected';
        $m->kyc_rejection_reason = $reason;
        $m->save();
        session()->flash('message', 'KYC rejected');
    }

    public function render()
    {
        $query = Membership::query()
            ->when($this->filter === 'pending', fn($q) => $q->where('kyc_status', 'pending'))
            ->when($this->filter === 'approved', fn($q) => $q->where('kyc_status', 'approved'))
            ->when($this->filter === 'rejected', fn($q) => $q->where('kyc_status', 'rejected'))
            ->when($this->search, function($q){
                $q->where(function($sub){
                    $sub->where('name','like','%'.$this->search.'%')
                        ->orWhere('membership_id','like','%'.$this->search.'%');
                });
            });

        return view('livewire.admin.kyc-verification', [
            'members' => $query->latest()->paginate(12)
        ]);
    }
}

