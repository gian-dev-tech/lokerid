<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:approvers,name',
        ]);

        $approver = Approver::create($request->only('name'));

        return response()->json($approver, 201);
    }
}
