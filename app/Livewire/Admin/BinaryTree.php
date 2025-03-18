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
        $this->treeData = $this->formatTreeData($this->root_id);
    }

    protected function formatTreeData($member_id, $level = 0)
    {
        if ($level > 10 || !$member_id) return null;

        $member = Membership::find($member_id);
        if (!$member) return null;

        $tree = BinaryTreeModel::where('parent_id', $member_id)->get();
        $left = $tree->where('position', 'left')->first();
        $right = $tree->where('position', 'right')->first();

        $data = [
            'name' => $member->name,
            'token' => $member->token,
            'status' => $member->isVerified ? 'verified' : 'pending'
        ];

        // Initialize children array
        $data['children'] = [];
        
        // Add left child
        $leftData = $left ? $this->formatTreeData($left->member_id, $level + 1) : null;
        if ($leftData) {
            $data['children'][] = $leftData;
        } else {
            $data['children'][] = [
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty',
                'children' => []
            ];
        }

        // Add right child
        $rightData = $right ? $this->formatTreeData($right->member_id, $level + 1) : null;
        if ($rightData) {
            $data['children'][] = $rightData;
        } else {
            $data['children'][] = [
                'name' => 'Empty',
                'token' => '',
                'status' => 'empty',
                'children' => []
            ];
        }

        return $data;
    }

    public function render()
    {
        return view('livewire.admin.binary-tree')->layout('components.layouts.admin');
    }
}
