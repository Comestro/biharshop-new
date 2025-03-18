<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\BinaryTree;

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
                $position = !in_array('left', $existingPositions) ? 'left' : 
                          (!in_array('right', $existingPositions) ? 'right' : null);

                if ($position) {
                    BinaryTree::create([
                        'member_id' => $this->selectedMembership->id,
                        'parent_id' => $referrer->id,
                        'position' => $position
                    ]);
                }
            } else {
                // If no referrer, find first member without full positions
                $availableSponsor = Membership::where('isVerified', true)
                    ->where('id', '!=', $this->selectedMembership->id)
                    ->whereDoesntHave('binaryChildren', function($q) {
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
                return $q->where(function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                          ->orWhere('email', 'like', '%'.$this->search.'%')
                          ->orWhere('mobile', 'like', '%'.$this->search.'%')
                          ->orWhere('token', 'like', '%'.$this->search.'%');
                });
            });

        return view('livewire.admin.members', [
            'members' => $query->latest()->paginate(10)
        ])->layout('components.layouts.admin');
    }
}
