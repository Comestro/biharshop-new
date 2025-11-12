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
    public $initial_root_id;
    public $root_history = [];
    public $searchQuery = '';
    public $maxDepth = 6; // default to 6 levels when not searching
    

    public function mount($root_id = null)
    {
        $this->initial_root_id = $root_id ?? Membership::where('isVerified', true)->first()?->id;
        $this->root_id = $this->initial_root_id;
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
        if (!$memberId) return;
        // Respect maxDepth when set (6 by default, unlimited when searching)
        if ($this->maxDepth !== null && $depth > $this->maxDepth) return;

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

        // If at depth cap, do not traverse children further
        if ($this->maxDepth !== null && $depth >= $this->maxDepth) {
            return;
        }

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
        if ($id && $id != $this->root_id) {
            if ($this->root_id) {
                $this->root_history[] = $this->root_id;
            }
            $this->root_id = $id;
        }
        $this->loadTree();
        // Dispatch an event with updated tree data (different name to avoid recursion)
        $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
    }

    // Toggle search mode: unlimited depth when query present, else cap at 6
    #[On('binaryTreeSearch')]
    public function applySearch($payload)
    {
        $query = is_array($payload) ? ($payload['query'] ?? '') : (string) $payload;
        $this->searchQuery = $query;
        $this->maxDepth = ($this->searchQuery !== '') ? null : 6;
        $this->loadTree();
        $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
    }

    // Navigate back to previous parent
    public function backToPreviousRoot()
    {
        if (!empty($this->root_history)) {
            $prev = array_pop($this->root_history);
            if ($prev) {
                $this->root_id = $prev;
                $this->loadTree();
                $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
            }
        }
    }

    // Reset to the initial root
    public function resetRoot()
    {
        $this->root_history = [];
        $this->root_id = $this->initial_root_id;
        $this->loadTree();
        $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
    }

    public function render()
    {
        return view('livewire.admin.binary-tree', [
            'treeData' => $this->treeData
        ]);
    }
}
