<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree as BinaryTreeModel;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

    #[Layout('components.layouts.admin')]
class BinaryTree extends Component
{
    public $root_id;
    public $treeData = [];
    

    public function mount($root_id = null)
    {
        $this->root_id = $root_id ?? Membership::where('isVerified', true)->first()?->id;
        $this->loadTree();
    }

    public function updatedRootId()
    {
        $this->loadTree();
    }

    public function loadTree()
    {
        if (!$this->root_id) {
            $this->treeData = [];
            return;
        }

        $flatData = [];
        $this->processNode($this->root_id, null, $flatData, 0);
        $this->treeData = $flatData;
    }

    protected function processNode($memberId, $parentId, &$flatData, $depth)
    {
        if (!$memberId || $depth > 5) return;

        $member = Membership::find($memberId);
        if (!$member) return;

        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->token,
            // mark the current root so the frontend can highlight it
            'status' => $member->id == $this->root_id ? 'current' : ($member->isVerified ? 'verified' : 'pending'),
            // helpful fields for the frontend (avatar or initials)
            'avatar' => $member->user?->avatar ?? null,
            'initials' => $member->user?->initials() ?? strtoupper(substr($member->name, 0, 1)),
        ];

        if ($depth >= 5) return;

        $tree = BinaryTreeModel::where('parent_id', $memberId)->get();

        $left = $tree->where('position', 'left')->first();
        if ($left) {
            $this->processNode($left->member_id, $member->id, $flatData, $depth + 1);
        } else {
            $flatData[] = [
                'id' => "empty-left-{$member->id}",
                'parentId' => $member->id,
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty'
            ];
        }

        $right = $tree->where('position', 'right')->first();
        if ($right) {
            $this->processNode($right->member_id, $member->id, $flatData, $depth + 1);
        } else {
            $flatData[] = [
                'id' => "empty-right-{$member->id}",
                'parentId' => $member->id,
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty'
            ];
        }
    }

    // Listen for browser/JS request to change root
    #[On('binaryTreeChangeRootRequest')]
    public function changeRootRequest($id)
    {
        $this->root_id = $id;
        $this->loadTree();
        // Dispatch an event with updated tree data (different name to avoid recursion)
        $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
    }

    public function render()
    {
        return view('livewire.admin.binary-tree', [
            'treeData' => $this->treeData
        ]);
    }
}
