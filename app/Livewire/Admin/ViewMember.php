<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;

class ViewMember extends Component
{
    public $member;
    public $activeTab = 'personal';

    protected $validTabs = ['personal', 'financial', 'network', 'tree'];
    protected $listeners = ['treeNodeSelected' => 'navigateToMember'];

    public function mount($id)
    {
        $this->member = Membership::with([
            'referrer',
            'referrals',
            'binaryPosition.parent',
            'children.member'
        ])->findOrFail($id);
    }

    public function setTab($tab)
    {
        if (in_array($tab, $this->validTabs)) {
            $this->activeTab = $tab;
            if ($tab === 'tree') {
                // Send tree data after tab change
                $this->dispatch('tabChanged', treeData: $this->getTreeData());
            }
        }
    }

    public function navigateToMember($memberId)
    {
        if ($memberId && !str_contains($memberId, 'empty')) {
            return redirect()->route('admin.members.view', $memberId);
        }
    }

    protected function getTreeData()
    {
        $flatData = [];
        $this->processNode($this->member->id, null, $flatData);
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
        return view('livewire.admin.view-member', [
            'treeData' => $this->getTreeData()
        ])->layout('components.layouts.admin');
    }
}

