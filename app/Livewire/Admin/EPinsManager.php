<?php

namespace App\Livewire\Admin;

use App\Models\EPin;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class EPinsManager extends Component
{
    public $generateCount = 10;
    public $planAmount = 3000;
    public $planName = 'Standard';
    public $assignEmail = '';
    public $transferCode = '';
    public $transferToEmail = '';

    public function generate()
    {
        $admin = auth('admin')->user();
        $owner = User::where('email', $this->assignEmail)->first();
        for ($i = 0; $i < max((int)$this->generateCount, 0); $i++) {
            $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            if (EPin::where('code', $code)->exists()) { $i--; continue; }
            EPin::create([
                'code' => $code,
                'plan_amount' => $this->planAmount,
                'plan_name' => $this->planName,
                'owner_user_id' => $owner?->id,
                'generated_by_admin_id' => $admin?->id,
                'status' => 'available',
            ]);
        }
        $this->dispatch('pins-generated');
    }

    public function transfer()
    {
        $pin = EPin::where('code', $this->transferCode)->where('status','!=','used')->first();
        $to = User::where('email', $this->transferToEmail)->first();
        if ($pin && $to) {
            $pin->update([
                'owner_user_id' => $to->id,
                'status' => 'transferred',
            ]);
            $this->dispatch('pin-transferred');
        }
    }

    public function render()
    {
        $pins = EPin::orderBy('created_at','desc')->limit(200)->get();
        return view('livewire.admin.epins-manager', ['pins' => $pins]);
    }
}

