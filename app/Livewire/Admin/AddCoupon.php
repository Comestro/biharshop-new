<?php

namespace App\Livewire\Admin;

use App\Models\Coupon;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Validation\Rule;

#[Layout('components.layouts.admin')]
class AddCoupon extends Component
{
    use WithPagination;

    public $code, $discount_type, $discount_value, $min_order_amount, $max_discount_amount;
    public $valid_from, $valid_until, $usage_limit, $used_count = 0, $status ;

    public $search = '';
    public $showModal = false;
    public $editMode = false;
    public $couponId;

    protected function rules()
    {
        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('coupons', 'code')->ignore($this->couponId)
            ],
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'used_count' => 'integer|min:0',
            'status' => 'string',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->editMode = true;
        $this->showModal = true;
        $this->couponId = $id;

        $coupon = Coupon::findOrFail($id);

        // Manual assignment instead of fill()
        $this->code = $coupon->code;
        $this->discount_type = $coupon->discount_type;
        $this->discount_value = $coupon->discount_value;
        $this->min_order_amount = $coupon->min_order_amount;
        $this->max_discount_amount = $coupon->max_discount_amount;
        $this->valid_from = Carbon::parse($coupon->valid_from)->format('Y-m-d');
        $this->valid_until = Carbon::parse($coupon->valid_until)->format('Y-m-d');
        $this->usage_limit = $coupon->usage_limit;
        $this->used_count = $coupon->used_count;
        $this->status = $coupon->status;
    }

    public function save()
    {
        $this->validate();

        $coupon = $this->editMode ? Coupon::findOrFail($this->couponId) : new Coupon();

        $coupon->code = $this->code;
        $coupon->discount_type = $this->discount_type;
        $coupon->discount_value = $this->discount_value;
        $coupon->min_order_amount = $this->min_order_amount;
        $coupon->max_discount_amount = $this->max_discount_amount;
        $coupon->valid_from = $this->valid_from;
        $coupon->valid_until = $this->valid_until;
        $coupon->usage_limit = $this->usage_limit;
        $coupon->used_count = $this->used_count ?? 0;
        $coupon->status = $this->status;

        $coupon->save();

        session()->flash('message', $this->editMode ? 'Coupon updated successfully!' : 'Coupon added successfully!');
        $this->showModal = false;
        $this->resetForm();
    }

    public function delete($id)
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('message', 'Coupon deleted successfully!');
    }

    public function resetForm()
    {
        $this->reset([
            'code',
            'discount_type',
            'discount_value',
            'min_order_amount',
            'max_discount_amount',
            'valid_from',
            'valid_until',
            'usage_limit',
            'used_count',
            'status',
            'editMode',
            'couponId'
        ]);
        $this->resetValidation();
    }

    public function render()
    {
        $coupons = Coupon::query()
            ->when($this->search, fn($q) => $q->where('code', 'like', '%' . $this->search . '%'))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.add-coupon', ['coupons' => $coupons]);
    }
}
