<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $guarded = [];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function epin()
    {
        return $this->belongsTo(EPin::class, 'epin_id');
    }
}

