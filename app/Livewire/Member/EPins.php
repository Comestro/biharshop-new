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
    public $transferToToken = '';
    public $redeemCode = '';

    public $tablename = 'epins';

    public function transfer()
    {
        $pin = EPin::where('code', $this->transferCode)->where('status','!=','used')->first();
        $toMember = Membership::where('token', $this->transferToToken)->first();
        if ($pin && $toMember) {
            if ($pin->owner_user_id === auth()->id()) {
                $pin->update(['owner_user_id' => $toMember->user_id, 'status' => 'transferred']);
                $this->dispatch('member-pin-transferred');
            }
        }
    }

    public function redeem() 
    {
        $pin = EPin::where('code', $this->redeemCode)->where('status','!=','used')->first();
        if ($pin && $pin->owner_user_id) {
            $sponsor = Membership::where('user_id', $pin->owner_user_id)->first();
            $member = Membership::where('user_id', auth()->id())->first();
            if ($sponsor && $member) {
                $member->referal_id = $sponsor->id;
                $member->used_pin_count = ($member->used_pin_count ?? 0) + 1;
                $member->save();
                if (! ReferralTree::where('member_id', $member->id)->exists()) {
                    ReferralTree::create(['member_id' => $member->id, 'parent_id' => $sponsor->id]);
                }
                $pin->update([
                    'used_by_membership_id' => $member->id,
                    'status' => 'used',
                    'used_at' => now(),
                ]);
                $this->dispatch('member-pin-redeemed');
            }
        }
    }

    public function render()
    {
        $pins = EPin::where('owner_user_id', auth()->id())->orderBy('created_at','desc')->get();
        return view('livewire.member.epins', ['pins' => $pins]);
    }
}

