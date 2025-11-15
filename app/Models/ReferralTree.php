<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralTree extends Model
{
    protected $guarded = [];
    public function member()
    {
        return $this->belongsTo(Membership::class, 'member_id');
    }
}
