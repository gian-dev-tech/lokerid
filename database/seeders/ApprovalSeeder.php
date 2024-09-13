<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Approver;
use App\Models\Expense;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusApproved = Status::where('name', 'disetujui')->first();
        $approvers = Approver::all();
        $expenses = Expense::all();

        foreach ($expenses as $expense) {
            foreach ($approvers as $approver) {
                Approval::create([
                    'expense_id' => $expense->id,
                    'approver_id' => $approver->id,
                    'status_id' => $statusApproved->id,
                ]);
            }
        }
    }
}
