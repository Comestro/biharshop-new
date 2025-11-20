<?php

namespace App\Livewire\Admin;

use App\Models\EPin;
use App\Models\Membership;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class EPinsManager extends Component
{
    public $generateCount = 10;
    public $planAmount = 3000;
    public $planName = 'Standard';
    public $membershipId = null;
    public $transferMemberName = null;

    public $transferCode = '';
    public $transferToEmail = '';

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
        $owner = Membership::where('membership_id', $this->membershipId)->first(); // may be null

        for ($i = 0; $i < max((int) $this->generateCount, 0); $i++) {

            // Unique random 6-digit code
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // If exists, regenerate
            if (EPin::where('code', $code)->exists()) {
                $i--;
                continue;
            }

            EPin::create([
                'code' => $code,
                'plan_amount' => $this->planAmount,
                'plan_name' => $this->planName,
                'owner_user_id' => $owner?->id,
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

    public function render()
    {
        $pins = EPin::orderBy('created_at', 'desc')->limit(200)->get();

        return view('livewire.admin.epins-manager', [
            'pins' => $pins
        ]);
    }
}
