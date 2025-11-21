<?php

namespace App\Livewire\Member;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;
#[Layout('components.layouts.member')]
class Tree extends Component
{
    public $root_id;
    public $treeData;

    public function mount()
    {
        $this->root_id = auth()->user()->membership->id;
        $this->loadTree();
    }

    protected function loadTree()
    {
        $flatData = [];
        $this->processNode($this->root_id, null, $flatData);
        $this->treeData = $flatData;
    }

    protected function processNode($memberId, $parentId, &$flatData)
    {
        if (!$memberId)
            return;

        $member = Membership::find($memberId);
        if (!$member)
            return;

        // Add current node with logged in user check
        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->membership_id,
            'status' => $member->id === auth()->user()->membership->id ? 'current' :
                ($member->isVerified ? 'verified' : 'pending')
        ];

        // Process children
        $tree = BinaryTree::where('parent_id', $memberId)->get();

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
        return view('livewire.member.tree');
    }
}
