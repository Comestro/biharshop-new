<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\BinaryPosition;
use Illuminate\Support\Facades\DB;

class ManagePositions extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedMember = null;
    public $viewingMember = null;
    public $sponsorId = '';
    public $position = '';

    protected $listeners = ['refreshTree' => '$refresh'];

    public function selectMember($id)
    {
        $this->selectedMember = Membership::find($id);
        if($position = $this->selectedMember->binaryPosition) {
            $this->position = $position->position;
            $this->sponsorId = $position->parent->token;
        }
    }

    public function cancelEdit()
    {
        $this->selectedMember = null;
        $this->reset(['sponsorId', 'position']);
    }

    public function closeTree()
    {
        $this->viewingMember = null;
    }

    public function updatePosition()
    {
        $this->validate([
            'sponsorId' => 'required|exists:memberships,token',
            'position' => 'required|in:left,right',
        ], [
            'sponsorId.required' => 'Please enter sponsor ID',
            'sponsorId.exists' => 'Invalid sponsor ID',
            'position.required' => 'Please select a position',
            'position.in' => 'Position must be either left or right'
        ]);

        try {
            DB::beginTransaction();

            $sponsor = Membership::where('token', $this->sponsorId)->first();
            $currentPosition = $this->selectedMember->binaryPosition;

            // Check if trying to assign under own child (prevent circular dependency)
            if ($this->isChildOf($sponsor->id, $this->selectedMember->id)) {
                $this->addError('sponsorId', "Cannot assign under own downline member");
                return;
            }

            // Check if new position is available under sponsor
            $existingPosition = BinaryPosition::where('parent_id', $sponsor->id)
                                            ->where('position', $this->position)
                                            ->exists();

            if ($existingPosition) {
                $this->addError('position', "Selected position is already taken");
                return;
            }

            if ($currentPosition) {
                // Update existing position
                $currentPosition->update([
                    'parent_id' => $sponsor->id,
                    'position' => $this->position
                ]);
            } else {
                // Create new position
                BinaryPosition::create([
                    'member_id' => $this->selectedMember->id,
                    'parent_id' => $sponsor->id,
                    'position' => $this->position
                ]);
            }

            DB::commit();
            session()->flash('message', 'Position updated successfully');
            $this->dispatch('refreshTree');
            $this->dispatch('closeModal');
            $this->reset(['selectedMember', 'sponsorId', 'position']);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('general', $e->getMessage());
        }
    }

    // Helper method to check if memberId is under parentId in the tree
    protected function isChildOf($memberId, $parentId)
    {
        // Get all upline members of the potential sponsor
        $uplineMembers = collect();
        $currentId = $memberId;

        while ($currentId) {
            $position = BinaryPosition::where('member_id', $currentId)->first();
            if (!$position) break;

            $currentId = $position->parent_id;
            $uplineMembers->push($currentId);

            // If we find the parent ID in upline, it would create a circular dependency
            if ($currentId === $parentId) {
                return true;
            }
        }

        return false;
    }

    public function viewTree($id)
    {
        $this->viewingMember = Membership::find($id);
    }

    public function render()
    {
        $members = Membership::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('token', 'like', '%'.$this->search.'%');
                });
            })
            ->where('isVerified', true)
            ->paginate(10);

        return view('livewire.admin.manage-positions', [
            'members' => $members
        ])->layout('components.layouts.admin');
    }
}
