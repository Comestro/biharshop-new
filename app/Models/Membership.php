<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function binaryPosition()
    {
        return $this->hasOne(BinaryTree::class, 'member_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Membership::class, 'referal_id');
    }

    public function referrals()
    {
        return $this->hasMany(Membership::class, 'referal_id');
    }

    public function binaryChildren()
    {
        return $this->hasMany(BinaryTree::class, 'parent_id');
    }
}
