<?php

namespace App\Livewire\User;

use App\Models\Address;
use Livewire\Component;
use Auth;

class MyAddress extends Component
{
    public $addresses;

    public $isOpenModal = false;
    public $editMode = false;
    public $address_id;

    public $address_line1, $near_by, $city, $state, $country, $postal_code, $phone;

    public function render()
    {
        $this->addresses = Address::where('user_id', Auth::id())->get();
        return view('livewire.user.my-address');
    }

    // open add modal
    public function openAddModel()
    {
        $this->resetFields();
        $this->editMode = false;
        $this->isOpenModal = true;
    }

    // open edit modal
    public function openEditModel($id)
    {
        $this->editMode = true;
        $address = Address::findOrFail($id);
        $this->address_id = $address->id;
        $this->address_line1 = $address->address_line1;
        $this->near_by = $address->near_by;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->country = $address->country;
        $this->postal_code = $address->postal_code;
        $this->phone = $address->phone;

        $this->isOpenModal = true;
    }

    // close modal
    public function closeModal()
    {
        $this->isOpenModal = false;
    }

    // add address
    public function addAddress()
    {
        Address::create([
            'user_id' => Auth::id(),
            'status' => 'active',
            'address_line1' => $this->address_line1,
            'near_by' => $this->near_by,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
        ]);

        $this->resetFields();
        $this->isOpenModal = false;
    }

    // update address
    public function updateAddress()
    {
        $address = Address::findOrFail($this->address_id);
        $address->update([
            'address_line1' => $this->address_line1,
            'near_by' => $this->near_by,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
        ]);

        $this->resetFields();
        $this->isOpenModal = false;
    }

    // delete address
    public function deleteAddress($id)
    {
        Address::where('id', $id)->where('user_id', Auth::id())->delete();
    }

    // reset fields
    private function resetFields()
    {
        $this->reset(['address_id','address_line1','near_by','city','state','country','postal_code','phone']);
    }
}
