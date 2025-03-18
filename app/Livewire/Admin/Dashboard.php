<?php
namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Membership;
use App\Models\Product;
use App\Models\Category;

class Dashboard extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $selectedMembership = null;
    public $showModal = false;

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

    public function render()
    {
        $stats = [
            'total_members' => Membership::count(),
            'pending_verifications' => Membership::where('isPaid', true)
                                               ->where('isVerified', false)
                                               ->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count()
        ];

        return view('livewire.admin.dashboard.index', [
            'stats' => $stats
        ])->layout('components.layouts.admin');
    }
}
