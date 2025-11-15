<?php

namespace App\Livewire\Member;

use App\Models\Membership;
use Livewire\Attributes\Layout;
use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
#[Layout('components.layouts.member')]
class MemberIdCard extends Component
{

    public function render()
    {
        $member = Membership::find(auth()->user()->membership->id);
        $data = [
            'name' => $member->name,
            'member_id' => $member->token,
            'dob' => $member->date_of_birth,
            'status' => $member->status ? 'Active' : 'Inactive',
            'join_year' => $member->created_at,
            'phone' => $member->phone
        ];
        $jsonData = json_encode($data);

        $qr = QrCode::size(80)->generate($jsonData); // jo bhi value ho

        return view('livewire.member.member-id-card', compact('member', 'qr'));
    }
}
