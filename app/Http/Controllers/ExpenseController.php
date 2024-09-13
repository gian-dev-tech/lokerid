<?php

namespace App\Http\Controllers;

use App\Models\Approval;
use App\Models\ApprovalStage;
use App\Models\Expense;
use App\Models\Status;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
   /**
 * @OA\Post(
 *     path="/api/expenses",
 *     tags={"Expense"},
 *     summary="Create a new expense",
 *     description="Create a new expense with a specified amount.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"amount"},
 *             @OA\Property(property="amount", type="integer", example=100, description="The amount of the expense.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Expense created successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="amount", type="integer", example=100),
 *             @OA\Property(property="status_id", type="integer", example=1)
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input.",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Validation failed")
 *         )
 *     )
 * )
 */


    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $expense = Expense::create([
            'amount' => $request->input('amount'),
            'status_id' => Status::where('name', 'menunggu persetujuan')->first()->id,
        ]);

        return response()->json($expense, 201);
    }
/**
 * @OA\Patch(
 *     path="/api/expense/{id}/approve",
 *     tags={"Expense"},
 *     summary="Approve an expense",
 *     description="Approve a specific expense by providing the approver ID.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the expense to approve.",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"approver_id"},
 *             @OA\Property(property="approver_id", type="integer", example=1, description="ID of the approver.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Expense approved successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Expense approved successfully.")
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error.",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */

    public function approve(Request $request, $id)
    {
        $request->validate([
            'approver_id' => 'required|exists:approvers,id',
        ]);
    
        $expense = Expense::findOrFail($id);
        $statusApproved = Status::where('name', 'disetujui')->first();
        $expense->status_id = $statusApproved->id;
        $expense->save();
    
        Approval::create([
            'expense_id' => $expense->id,
            'approver_id' => $request->approver_id,
            'status_id' => $statusApproved->id,
        ]);
    
        return response()->json(['message' => 'Expense approved successfully.'], 200);
    }
    /**
 * @OA\Get(
 *     path="/api/expenses/{id}",
 *     tags={"Expense"},
 *     summary="Get expense details",
 *     description="Retrieve details of a specific expense, including status and approval history.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the expense.",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Expense details retrieved successfully.",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="amount", type="integer", example=100),
 *             @OA\Property(
 *                 property="status",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="menunggu persetujuan")
 *             ),
 *             @OA\Property(
 *                 property="approval",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(
 *                         property="approver",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Approver A")
 *                     ),
 *                     @OA\Property(
 *                         property="status",
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="disetujui")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Expense not found.",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Expense not found")
 *         )
 *     )
 * )
 */

 public function show($id)
 {
     try {
         $expense = Expense::with(['status', 'approvals.approver'])->findOrFail($id);
         
         $response = [
             'id' => $expense->id,
             'amount' => $expense->amount,
             'status' => [
                 'id' => $expense->status->id,
                 'name' => $expense->status->name,
             ],
             'approval' => $expense->approvals->map(function ($approval) {
                 return [
                     'id' => $approval->id,
                     'approver' => [
                         'id' => $approval->approver->id,
                         'name' => $approval->approver->name,
                     ],
                     'status' => [
                         'id' => $approval->status->id,
                         'name' => $approval->status->name,
                     ],
                 ];
             }),
         ];
 
         return response()->json($response);
     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

         return response()->json([
             'success' => false,
             'message' => 'Expense not found.'
         ], 404);
     } catch (\Exception $e) {
         return response()->json([
             'success' => false,
             'message' => 'An error occurred while retrieving the expense.'
         ], 500);
     }
 }
 
}
