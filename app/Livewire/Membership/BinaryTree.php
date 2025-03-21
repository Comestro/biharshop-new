<?php
namespace App\Livewire\Membership;

use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree as BinaryTreeModel;

class BinaryTree extends Component
{
    public $root_id;
    
    public function mount($root_id = null)
    {
        $this->root_id = $root_id ?? auth()->user()->membership->id;
    }

    protected function getTreeData($member_id, $level = 0)
    {
        if ($level > 3) return null; 

        $member = Membership::find($member_id);
        if (!$member) return null;

        $left = BinaryTreeModel::where('parent_id', $member_id)
                            ->where('position', 'left')
                            ->first();

        $right = BinaryTreeModel::where('parent_id', $member_id)
                             ->where('position', 'right')
                             ->first();
                
        return [
            'member' => $member,
            'left' => $left ? $this->getTreeData($left->member_id, $level + 1) : null,
            'right' => $right ? $this->getTreeData($right->member_id, $level + 1) : null
        ];
    }

    public function render()
    {
        $treeData = $this->getTreeData($this->root_id);
        return view('livewire.membership.binary-tree', ['treeData' => $treeData]);
    }
}
