<?php

namespace App\Http\Controllers;

use App\Models\ApprovalStage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApprovalStageController extends Controller
{
    /**
 * @OA\Post(
 *     path="/approval-stages",
 *     tags={"Approval Stages"},
 *     summary="Create a new approval stage",
 *     description="Create a new approval stage by providing the approver's ID. Returns a JSON response with the created approval stage or validation errors.",
 *     operationId="storeApprovalStage",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"approver_id"},
 *             @OA\Property(
 *                 property="approver_id",
 *                 type="integer",
 *                 description="The ID of the approver"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Approval stage created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Approval stage created successfully."),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="approver_id", type="integer", example=1),
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation failed."),
 *             @OA\Property(property="errors", type="object",
 *                 @OA\Property(property="approver_id", type="array", @OA\Items(type="string", example="The selected approver_id is invalid.")),
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong."),
 *             @OA\Property(property="error", type="string", example="Error message")
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="approver_id",
 *         in="query",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="ID of the approver"
 *     ),
 * )
 */

    public function store(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'approver_id' => 'required|exists:approvers,id|unique:approval_stages,approver_id',
            ]);
    
            // Membuat Approval Stage baru
            $stage = ApprovalStage::create($request->only('approver_id'));
    
            // Mengembalikan respons JSON berhasil
            return response()->json([
                'success' => true,
                'message' => 'Approval stage created successfully.',
                'data' => $stage,
            ], 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mengembalikan respons JSON gagal dengan pesan error validasi
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Mengembalikan respons JSON gagal untuk error lainnya
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

/**
 * @OA\Put(
 *     path="/approval-stages/{id}",
 *     tags={"Approval Stages"},
 *     summary="Update an approval stage",
 *     description="Update an approval stage by providing the approver's ID. Returns a JSON response with the updated approval stage or validation errors.",
 *     operationId="updateApprovalStage",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer"),
 *         description="The ID of the approval stage to be updated"
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"approver_id"},
 *             @OA\Property(
 *                 property="approver_id",
 *                 type="integer",
 *                 description="The ID of the approver"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Approval stage updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Approval stage updated successfully."),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="approver_id", type="integer", example=1),
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation failed."),
 *             @OA\Property(property="errors", type="object",
 *                 @OA\Property(property="approver_id", type="array", @OA\Items(type="string", example="The selected approver_id is invalid.")),
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Something went wrong."),
 *             @OA\Property(property="error", type="string", example="Error message")
 *         )
 *     ),
 * )
 */

    public function update(Request $request, $id)
    {
        try {
            // Validasi input
            $request->validate([
                'approver_id' => [
                    'required',
                    'exists:approvers,id',
                    Rule::unique('approval_stages', 'approver_id')->ignore($id)
                ],
            ]);
    
            // Mencari Approval Stage berdasarkan ID
            $stage = ApprovalStage::findOrFail($id);
    
            // Mengupdate Approval Stage
            $stage->update($request->only('approver_id'));
    
            // Mengembalikan respons JSON berhasil
            return response()->json([
                'success' => true,
                'message' => 'Approval stage updated successfully.',
                'data' => $stage,
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mengembalikan respons JSON gagal dengan pesan error validasi
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Mengembalikan respons JSON gagal untuk error lainnya
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
}
