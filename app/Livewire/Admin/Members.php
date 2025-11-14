<?php

namespace App\Livewire\Admin;

use App\Models\ReferralTree;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\BinaryTree;
use Illuminate\Support\Facades\Http;
#[Layout('components.layouts.admin')]
class Members extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $selectedMembership = null;
    public $showModal = false;

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => '']
    ];

    public function showMemberDetails($id)
    {
        $this->selectedMembership = Membership::find($id);
        $this->showModal = true;
    }

    public function approveMember()
    {
        if ($this->selectedMembership) {
            $this->selectedMembership->update([
                'isVerified' => true
            ]);

            // Handle binary position if referral exists
            if ($this->selectedMembership->referal_id) {
                $referrer = Membership::find($this->selectedMembership->referal_id);

                // Check available positions under referrer
                $existingPositions = BinaryTree::where('parent_id', $referrer->id)
                    ->pluck('position')
                    ->toArray();

                // Assign to first available position
                $position = !in_array('left', $existingPositions) ? 'left' : (!in_array('right', $existingPositions) ? 'right' : null);

                if ($position) {
                    BinaryTree::create([
                        'member_id' => $this->selectedMembership->id,
                        'parent_id' => $referrer->id,
                        'position' => $position
                    ]);
                }
            } elseif ($this->selectedMembership->membership_id) {
                $membershipper = Membership::find($this->selectedMembership->membership_id);
                if ($membershipper) {
                    ReferralTree::create([
                        'member_id' => $this->selectedMembership->id,
                        'parent_id' => $this->selectedMembership->membership_id,
                        'level' => $membershipper->level + 1 ?? 0
                    ]);
                }


            } else {
                // If no referrer, find first member without full positions
                $availableSponsor = Membership::where('isVerified', true)
                    ->where('id', '!=', $this->selectedMembership->id)
                    ->whereDoesntHave('binaryChildren', function ($q) {
                        $q->whereIn('position', ['left', 'right']);
                    })
                    ->first();

                if ($availableSponsor) {
                    BinaryTree::create([
                        'member_id' => $this->selectedMembership->id,
                        'parent_id' => $availableSponsor->id,
                        'position' => 'left'
                    ]);
                }
            }

            $this->showModal = false;
            session()->flash('message', 'Member verified and positioned successfully.');

            $this->sendMembershipMessage($this->selectedMembership->mobile, $this->selectedMembership->name, $this->selectedMembership->token);
        }
    }

    private function sendMembershipMessage($mobile, $name, $membershipid)
    {
        $response = Http::withHeaders([
            'authkey' => env('MSG91_AUTH_KEY'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://control.msg91.com/api/v5/flow', [
                    'template_id' => '683e9d01d6fc053f9a6f39d3', // replace with your approved template ID
                    'short_url' => 0, // 1 to enable short links, 0 to disable
                    'recipients' => [
                        [
                            'mobiles' => '91' . $mobile,
                            'name' => $name,
                            'membershipid' => $membershipid,
                        ]
                    ]
                ]);

        if ($response->successful()) {
            return response()->json(['message' => 'SMS sent successfully']);
        } else {
            return response()->json(['error' => 'Failed to send SMS', 'details' => $response->body()], 500);
        }
    }

    public function render()
    {
        $query = Membership::query()
            ->when($this->statusFilter === 'pending', function ($q) {
                return $q->where('isPaid', true)->where('isVerified', false);
            })
            ->when($this->statusFilter === 'verified', function ($q) {
                return $q->where('isVerified', true);
            })
            ->when($this->statusFilter === 'unpaid', function ($q) {
                return $q->where('isPaid', false);
            })
            ->when($this->search, function ($q) {
                return $q->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('mobile', 'like', '%' . $this->search . '%')
                        ->orWhere('token', 'like', '%' . $this->search . '%');
                });
            });

        return view('livewire.admin.members', [
            'members' => $query->latest()->paginate(10)
        ]);
    }
}
