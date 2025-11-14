<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;
#[Layout('components.layouts.admin')]
class ViewMember extends Component
{
    public $member;
    public $activeTab = 'personal';
    public $leftTeamSize = 0;
    public $rightTeamSize = 0;
    public $totalTeamSize = 0;
   
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
        
        // Calculate team sizes
        $this->calculateTeamSizes();
    }
    
    protected function calculateTeamSizes()
    {
        $this->leftTeamSize = 0;
        $this->rightTeamSize = 0;
        
        if ($this->member) {
            // Get left and right legs first
            $leftChild = BinaryTree::where('parent_id', $this->member->id)
                ->where('position', 'left')
                ->first();
                
            $rightChild = BinaryTree::where('parent_id', $this->member->id)
                ->where('position', 'right')
                ->first();
            
            // Count members in left leg
            if ($leftChild) {
                $this->leftTeamSize = 1 + $this->countTreeMembers($leftChild->member_id);
            }
            
            // Count members in right leg
            if ($rightChild) {
                $this->rightTeamSize = 1 + $this->countTreeMembers($rightChild->member_id);
            }
            
            // Calculate total team size
            $this->totalTeamSize = $this->leftTeamSize + $this->rightTeamSize;
        }
    }
    
    protected function countTreeMembers($memberId)
    {
        $count = 0;
        $children = BinaryTree::where('parent_id', $memberId)->get();
        
        foreach ($children as $child) {
            $count++; // Count this child
            $count += $this->countTreeMembers($child->member_id); // Add all descendants
        }
        
        return $count;
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
        return view('livewire.admin.view-member');
    }
}

