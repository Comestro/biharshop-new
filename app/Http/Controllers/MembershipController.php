<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\BinaryPosition;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            // Add other validation rules
        ]);

        $membership = Membership::create([
            'token' => uniqid('MEM'),
            'user_id' => auth()->id(),
            ...$validated
        ]);

        return response()->json(['message' => 'Registration successful', 'membership' => $membership]);
    }

    public function assignPosition(Request $request)
    {
        $validated = $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'parent_id' => 'required|exists:memberships,id',
            'position' => 'required|in:left,right'
        ]);

        $position = BinaryPosition::create($validated);

        return response()->json(['message' => 'Position assigned successfully']);
    }
}
