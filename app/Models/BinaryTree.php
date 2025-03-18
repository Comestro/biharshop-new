<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryTree extends Model
{
    protected $fillable = [
        'member_id',
        'parent_id',
        'position'
    ];

    public function member()
    {
        return $this->belongsTo(Membership::class, 'member_id');
    }

    public function parent()
    {
        return $this->belongsTo(Membership::class, 'parent_id');
    }
}
