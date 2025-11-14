<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Withdrawal;
use App\Models\Membership;

#[Layout('components.layouts.admin')]
class Withdrawals extends Component
{
    use WithPagination;

    public $status = 'pending';
    public $search = '';

    public function approve($id)
    {
        $w = Withdrawal::find($id);
        if ($w && $w->status === 'pending') {
            $w->status = 'approved';
            $w->save();
        }
    }

    public function reject($id)
    {
        $w = Withdrawal::find($id);
        if ($w && $w->status === 'pending') {
            $w->status = 'rejected';
            $w->save();
        }
    }

    public function render()
    {
        $query = Withdrawal::query()
            ->with('membership')
            ->when($this->status !== 'all', function ($q) {
                $q->where('status', $this->status);
            })
            ->when($this->search, function ($q) {
                $q->whereHas('membership', function ($mq) {
                    $mq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('token', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc');

        return view('livewire.admin.withdrawals', [
            'withdrawals' => $query->paginate(15)
        ]);
    }
}

