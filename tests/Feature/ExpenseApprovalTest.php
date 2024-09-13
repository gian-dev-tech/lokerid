<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Approver;
use App\Models\ApprovalStage;
use App\Models\Expense;
use App\Models\Status;

class ExpenseApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_expense()
    {
        $status = Status::create(['name' => 'menunggu persetujuan']);
        $response = $this->post('/api/expense', ['amount' => 100]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', ['amount' => 100]);
    }

    public function test_can_approve_expense()
    {
        $statusPending = Status::create(['name' => 'menunggu persetujuan']);
        $statusApproved = Status::create(['name' => 'disetujui']);
        $approver = Approver::create(['name' => 'Approver A']);
        $expense = Expense::create(['amount' => 100, 'status_id' => $statusPending->id]);
        $approvalStage = ApprovalStage::create(['approver_id' => $approver->id]);

        $response = $this->patch('/api/expense/' . $expense->id . '/approve', ['approver_id' => $approver->id]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('approvals', ['expense_id' => $expense->id, 'approver_id' => $approver->id, 'status_id' => $statusApproved->id]);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'status_id' => $statusApproved->id]);
    }

    public function test_can_get_expense()
    {
        $status = Status::create(['name' => 'menunggu persetujuan']);
        $expense = Expense::create(['amount' => 100, 'status_id' => $status->id]);

        $response = $this->get('/api/expense/' . $expense->id);

        $response->assertStatus(200);
        $response->assertJson([
            'id' => $expense->id,
            'amount' => $expense->amount,
            'status' => ['id' => $status->id, 'name' => $status->name],
        ]);
    }
}
