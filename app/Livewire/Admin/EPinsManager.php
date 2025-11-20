<?php

namespace App\Livewire\Admin;

use App\Models\EPin;
use App\Models\Membership;
use App\Models\User;
use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class EPinsManager extends Component
{
    public $generateCount = 10;
    public $selectedPlanId = null;

    public $filterStatus = 'all';
    public $filterOwnerEmail = '';
    public $filterPlanId = null;

    public $bulkTransferQty = 0;
    public $bulkTransferMemberId = '';

    public function updatedMembershipId($value)
    {
        if (!$value) {
            $this->transferMemberName = null;
            return;
        }

        $member = Membership::where('membership_id', $value)->first();

        $this->transferMemberName = $member ? $member->name : 'Not Found';
    }


    public function generate()
    {
        $admin = auth('admin')->user();
        $plan = $this->selectedPlanId ? Plan::find($this->selectedPlanId) : null;
        if (! $plan) { return; }
        for ($i = 0; $i < max((int)$this->generateCount, 0); $i++) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // If exists, regenerate
            if (EPin::where('code', $code)->exists()) {
                $i--;
                continue;
            }

            EPin::create([
                'code' => $code,
                'plan_amount' => $plan->price,
                'plan_name' => $plan->name,
                'plan_id' => $plan->id,
                'owner_user_id' => null,
                'generated_by_admin_id' => $admin?->id,
                'status' => 'available',
            ]);
        }
        $this->reset();
        $this->dispatch('pins-generated');
    }


    public function transfer()
    {
        $pin = EPin::where('code', $this->transferCode)
            ->where('status', '!=', 'used')
            ->first();

        $to = User::where('email', $this->transferToEmail)->first();

        if ($pin && $to) {
            $pin->update([
                'owner_user_id' => $to->id,
                'status' => 'transferred'
            ]);

            $this->dispatch('pin-transferred');
        }
    }

    public function bulkTransfer()
    {
        $member = \App\Models\Membership::where('membership_id', $this->bulkTransferMemberId)->first();
        if (! $member || ! $member->user_id) { return; }
        $limit = max((int)$this->bulkTransferQty, 0);
        if ($limit <= 0) { return; }
        $pins = EPin::where('status','available')->limit($limit)->get();
        foreach ($pins as $pin) {
            $pin->update([
                'owner_user_id' => $member->user_id,
                'status' => 'transferred',
            ]);
        }
        $this->dispatch('pin-transferred');
        $this->bulkTransferQty = 0;
        $this->bulkTransferMemberId = '';
    }

    public function render()
    {
        $query = EPin::query()->orderBy('created_at','desc');
        if ($this->filterStatus !== 'all') {
            $query->where('status', $this->filterStatus);
        }
        if ($this->filterPlanId) {
            $query->where('plan_id', $this->filterPlanId);
        }
        if ($this->filterOwnerEmail) {
            $query->whereHas('owner', function($q) {
                $q->where('email','like','%'.$this->filterOwnerEmail.'%');
            });
        }
        $pins = $query->limit(200)->get();
        $plans = Plan::orderBy('name')->get();
        return view('livewire.admin.epins-manager', [
            'pins' => $pins,
            'plans' => $plans,
        ]);
    }
}
