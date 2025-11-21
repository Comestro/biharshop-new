<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = [];

    public function plans()
    {
        return $this->hasMany(MembershipPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function binaryPosition()
    {
        return $this->hasOne(BinaryPosition::class, 'member_id');
    }

    public function binaryChildren()
    {
        return $this->hasMany(BinaryPosition::class, 'parent_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Membership::class, 'referal_id');
    }

    public function referrals()
    {
        return $this->hasMany(Membership::class, 'referal_id');
    }

    public function children()
    {
        return $this->hasMany(BinaryPosition::class, 'parent_id');
    }

    public function isKycComplete(): bool
    {
        $required = [
            'name','mobile','date_of_birth','gender','nationality','marital_status','father_name','mother_name',
            'home_address','city','state','nominee_name','nominee_relation','bank_name','branch_name','account_no','ifsc',
            'pancard','aadhar_card','pancard_image','aadhar_card_image'
        ];
        foreach ($required as $field) {
            if (empty($this->{$field})) {
                return false;
            }
        }
        if (! $this->terms_and_condition) {
            return false;
        }
        return true;
    }
}
