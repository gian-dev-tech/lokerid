<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusPending = Status::where('name', 'menunggu persetujuan')->first();

        Expense::create([
            'amount' => 500,
            'status_id' => $statusPending->id,
        ]);

        Expense::create([
            'amount' => 1000,
            'status_id' => $statusPending->id,
        ]);
    }
}
