<?php

namespace App\Livewire\Member;

use App\Models\BinaryTree;
use App\Models\Membership;
use App\Models\User;
use App\Models\WalletTransaction;
use Livewire\Attributes\On;
use Livewire\Component;

class MemberTreeComponent extends Component
{
    public $root_id;
    public $treeData = [];
    public $initial_root_id;
    public $root_history = [];
    public $searchQuery = ''; 
    public $maxDepth = 2000; // default to 6 levels when not searching
    public $showCreateModal = false;
    public $createParentId = null;
    public $createPosition = null;
    public $createForm = [
        'name' => '',
        'email' => '',
        'mobile' => '',
        'password' => '',
        'password_confirmation' => '',
    ];


    public function mount()
    {
        $selfId = auth()->user()?->membership?->id;
        $this->initial_root_id = $root_id ?? $selfId ?? Membership::where('isVerified', true)->first()?->id;
        $this->root_id = $this->initial_root_id;
        $this->createParentId = $this->root_id;
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
        if (!$memberId)
            return;
        // Respect maxDepth when set (6 by default, unlimited when searching)
        if ($this->maxDepth !== null && $depth > $this->maxDepth)
            return;

        $member = Membership::find($memberId);
        if (!$member)
            return;

        $flatData[] = [
            'id' => $member->id,
            'parentId' => $parentId,
            "membership_id" => $member->membership_id,
            'name' => $member->name,
            'token' => $member->membership_id,
            // mark the current root so the frontend can highlight it
            'status' => $member->id == $this->root_id ? 'current' : ($member->isVerified ? 'verified' : 'pending'),
            // helpful fields for the frontend (avatar or initials)
            'avatar' => $member->user?->avatar ?? null,
            'initials' => $member->user?->initials() ?? strtoupper(substr($member->name, 0, 1)),
            'binary_income' => WalletTransaction::where('membership_id', $member->id)
                ->where('type', 'binary_commission')->where('status', 'confirmed')
                ->sum('amount'),
            'total_income' => WalletTransaction::where('membership_id', $member->id)
                ->whereIn('type', ['binary_commission', 'referral_commission'])
                ->where('status', 'confirmed')
                ->sum('amount'),
        ];

        // If at depth cap, do not traverse children further
        if ($this->maxDepth !== null && $depth >= $this->maxDepth) {
            return;
        }

        $tree = BinaryTree::where('parent_id', $memberId)->get();

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

    // Open create modal for empty slot
    #[On('binaryTreeOpenCreateAtEmpty')]
    public function openCreateAtEmpty($parentId = null, $position = null)
    {

        if (is_array($parentId)) {
            $this->createParentId = $parentId['parentId'] ?? null;
            $this->createPosition = $parentId['position'] ?? null;
        } else {
            $this->createParentId = $parentId;
            $this->createPosition = $position;
        }

        if (!$this->createParentId || !in_array($this->createPosition, ['left', 'right'])) {
            return;
        }
    }

    // Create a new user + membership at an empty position under a parent
    #[On('binaryTreeCreateAtEmpty')]
    public function createAtEmpty($parentIdOrPayload = null, $position = null)
    {
        // Support both payload object and positional args
        if (is_array($parentIdOrPayload)) {
            $parentId = $parentIdOrPayload['parentId'] ?? null;
            $position = $parentIdOrPayload['position'] ?? null;
        } else {
            $parentId = $parentIdOrPayload;
        }
        if (!$parentId || !in_array($position, ['left', 'right'])) {
            return;
        }

        // Validate that the slot is still empty
        $exists = BinaryTree::where('parent_id', $parentId)
            ->where('position', $position)
            ->exists();
        if ($exists) {
            // Slot already filled; just reload
            $this->loadTree();
            $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
            return;
        }

        // Gather form values if present; otherwise generate defaults
        $this->validate([
            'createForm.name' => 'required|string|max:255',
            'createForm.email' => 'required|email|max:255|unique:users,email',
            'createForm.mobile' => 'nullable|string|max:20',
            'createForm.password' => 'required|string|min:8|confirmed',
        ]);

        $token = uniqid('MEM');
        $name = $this->createForm['name'];
        $email = $this->createForm['email'];
        $mobile = $this->createForm['mobile'] ?: null;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($this->createForm['password']),
        ]);

        // Create membership linked to the user
        $membership = Membership::create([
            'user_id' => $user->id,
            'token' => $token,
            'name' => $name,
            'email' => $email,
            'mobile' => $mobile,
            'isVerified' => true,
            'isPaid' => true,
            'status' => true,
        ]);

        // Position in binary tree under the parent
        BinaryTree::create([
            'member_id' => $membership->id,
            'parent_id' => $parentId,
            'position' => $position,
        ]);

        // Reload and notify frontend
        $this->loadTree();
        $this->dispatch('binaryTreeDataUpdated', treeData: $this->treeData);
        $this->resetCreateModal();
    }

    public function confirmCreateAtEmpty()
    {
        if (!$this->createParentId || !in_array($this->createPosition, ['left', 'right'])) {
            return;
        }
        $this->createAtEmpty($this->createParentId, $this->createPosition);
    }

    public function cancelCreateAtEmpty()
    {
        $this->resetCreateModal();
    }

    protected function resetCreateModal()
    {
        $this->showCreateModal = false;
        $this->createParentId = null;
        $this->createPosition = null;
        $this->createForm = ['name' => '', 'email' => '', 'mobile' => '', 'password' => '', 'password_confirmation' => ''];
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
        return view('livewire.member.member-tree-component', [
            'treeData' => $this->treeData
        ]);
    }
}
