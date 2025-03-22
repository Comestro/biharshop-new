<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree as BinaryTreeModel;

class BinaryTree extends Component
{
    public $root_id;
    public $treeData;

    public function mount($root_id = null)
    {
        $this->root_id = $root_id ?? Membership::where('isVerified', true)->first()?->id;
        $this->treeData = $this->formatTreeData();
    }

    protected function formatTreeData()
    {
        $flatData = [];
        $this->processNode($this->root_id, null, $flatData);
        return $flatData;
    }

    protected function processNode($memberId, $parentId, &$flatData)
    {
        if (!$memberId) return;

        $member = Membership::find($memberId);
        if (!$member) return;

        // Add current node to flat data
        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->token,
            'status' => $member->isVerified ? 'verified' : 'pending'
        ];

        // Process children
        $tree = BinaryTreeModel::where('parent_id', $memberId)->get();

        // Process left child
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

        // Process right child
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

    public function render()
    {
        return view('livewire.admin.binary-tree')->layout('components.layouts.admin');
    }
}
