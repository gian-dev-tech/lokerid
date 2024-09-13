<?php

namespace App\Http\Controllers;

use App\Models\ApprovalStage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApprovalStageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'approver_id' => 'required|exists:approvers,id|unique:approval_stages,approver_id',
        ]);

        $stage = ApprovalStage::create($request->only('approver_id'));

        return response()->json($stage, 201);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'approver_id' => [
                'required',
                'exists:approvers,id',
                Rule::unique('approval_stages', 'approver_id')->ignore($id)
            ],
        ]);

        $stage = ApprovalStage::findOrFail($id);
        $stage->update($request->only('approver_id'));

        return response()->json($stage);
    }
}
