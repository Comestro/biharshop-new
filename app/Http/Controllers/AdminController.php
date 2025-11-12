<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\Product;
use App\Models\Category;
use App\Models\BinaryTree as BinaryTreeModel;

class AdminController extends Controller
{
    public $statusFilter = 'all';
    public $search = '';
    public $selectedMembership = null;
    public $showModal = false;
    public $root_id;
    public $treeData = [];

    public function members()
    {
        $query = Membership::query()
            ->when($this->statusFilter === 'pending', function ($q) {
                return $q->where('isPaid', true)->where('isVerified', false);
            })
            ->when($this->statusFilter === 'verified', function ($q) {
                return $q->where('isVerified', true);
            })
            ->when($this->statusFilter === 'unpaid', function ($q) {
                return $q->where('isPaid', false);
            })
            ->when($this->search, function ($q) {
                return $q->where(function($query) {
                    $query->where('name', 'like', '%'.$this->search.'%')
                          ->orWhere('email', 'like', '%'.$this->search.'%')
                          ->orWhere('mobile', 'like', '%'.$this->search.'%')
                          ->orWhere('token', 'like', '%'.$this->search.'%');
                });
            });

        return view('livewire.admin.members', [
            'members' => $query->paginate(10)
        ])->layout('components.layouts.admin');
    }

    public function showMemberDetails($id)
    {
        $this->selectedMembership = Membership::find($id);
        $this->showModal = true;
    }

    public function approveMember()
    {
        if ($this->selectedMembership) {
            $this->selectedMembership->update([
                'isVerified' => true
            ]);
            $this->showModal = false;
            session()->flash('message', 'Member verified successfully.');
        }
    }

    
    public function dashboard()
    {
        $stats = [
            'total_members' => Membership::count(),
            'pending_verifications' => Membership::where('isPaid', true)
                                               ->where('isVerified', false)
                                               ->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count()
        ];

        return view('admin.dashboard', [
            'stats' => $stats
        ]);
    }

    // Binary Tree Methods
    public function binaryTree($root_id = null)
    {
        if (!$root_id) {
            $root_id = Membership::where('isVerified', true)->first()?->id;
        }

        $this->root_id = $root_id;
        $this->loadTree();

        return view('livewire.admin.binary-tree', [
            'treeData' => $this->treeData,
            'root_id' => $this->root_id
        ]);
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
        return response()->json([
            'success' => true,
            'treeData' => $this->treeData
        ]);
    }
}
