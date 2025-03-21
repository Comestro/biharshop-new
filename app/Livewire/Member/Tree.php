<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;

class Tree extends Component
{
    public $root_id;
    public $treeData;
    
    public function mount($root_id = null)
    {
        $this->root_id = $root_id ?? auth()->user()->membership->id;
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

        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            'name' => $member->name,
            'token' => $member->token,
            'status' => $member->isVerified ? 'verified' : 'pending'
        ];

        $tree = BinaryTree::where('parent_id', $memberId)->get();
        
        $left = $tree->where('position', 'left')->first();
        $right = $tree->where('position', 'right')->first();

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
        return view('livewire.member.tree')
                ->layout('components.layouts.member');
    }
}
