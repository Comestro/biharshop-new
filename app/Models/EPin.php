<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EPin extends Model
{
    use HasFactory;

    protected $table = 'epins';

    protected $fillable = [
        'code','plan_amount','plan_name','owner_user_id','generated_by_admin_id','used_by_membership_id','status','used_at'
    ];

    protected $casts = [
        'plan_amount' => 'float',
        'used_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function usedBy()
    {
        return $this->belongsTo(Membership::class, 'used_by_membership_id');
    }
}
