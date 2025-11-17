<?php

namespace App\Livewire\Member;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\Membership;
use App\Models\BinaryTree;

#[Layout('components.layouts.member')]
class WelcomeLetter extends Component
{
    public $token;
    public $member;
    public $parent;

    public function mount()
    {
        $this->member = auth()->user()->membership;
        $this->token = $this->member?->token;
        $this->parent = null;
        if ($this->member) {
            $pos = BinaryTree::where('member_id', $this->member->id)->first();
            if ($pos && $pos->parent_id) {
                $this->parent = Membership::find($pos->parent_id);
            } elseif ($this->member->referal_id) {
                $this->parent = Membership::find($this->member->referal_id);
            }
        }
    }

    public function render()
    {
        return view('livewire.member.welcome-letter', [
            'member' => $this->member,
            'token' => $this->token,
            'parent' => $this->parent,
        ]);
    }
}

