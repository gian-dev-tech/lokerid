<?php

namespace Database\Seeders;

use App\Models\ApprovalStage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApprovalStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApprovalStage::create(['approver_id' => 1]); // Approver A
        ApprovalStage::create(['approver_id' => 2]); // Approver B
        ApprovalStage::create(['approver_id' => 3]); // Approver C
    }
}
