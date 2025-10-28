<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree as BinaryTreeModel;

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
        $this->processNode($this->root_id, null, $flatData);
        $this->treeData = $flatData;
    }

    protected function processNode($memberId, $parentId, &$flatData)
    {
        if (!$memberId) return;

        $member = Membership::find($memberId);
        if (!$member) return;

        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->token,
            'status' => $member->isVerified ? 'verified' : 'pending'
        ];

        $tree = BinaryTreeModel::where('parent_id', $memberId)->get();

        $left = $tree->where('position', 'left')->first();
        if ($left) {
            $this->processNode($left->member_id, $member->id, $flatData);
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
            $this->processNode($right->member_id, $member->id, $flatData);
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

    public function changeRoot($id)
    {
        $this->root_id = $id;
        $this->loadTree();
        $this->dispatch('treeUpdated', treeData: $this->treeData);
    }

    public function render()
    {
        return view('livewire.admin.binary-tree', [
            'treeData' => $this->treeData
        ]);
    }
}
