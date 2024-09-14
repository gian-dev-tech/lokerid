<?php

namespace App\Http\Controllers;

use App\Models\Approver;
use Illuminate\Http\Request;

class ApproverController extends Controller
{
    /**
 * @OA\Post(
 *     path="/approvers",
 *     tags={"Approvers"},
 *     summary="Create a new approver",
 *     description="Create a new approver by providing a unique approver name. Returns a JSON response with the created approver or validation errors.",
 *     operationId="createApprover",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name"},
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="The name of the approver"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Approver created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Approver created successfully."),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="John Doe"),
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
 *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name has already been taken.")),
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

    public function store(Request $request)
{
    try {
        // Validasi input
        $request->validate([
            'name' => 'required|unique:approvers,name',
        ]);

        // Membuat approver baru
        $approver = Approver::create($request->only('name'));

        // Mengembalikan respons JSON berhasil
        return response()->json([
            'success' => true,
            'message' => 'Approver created successfully.',
            'data' => $approver,
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

}
