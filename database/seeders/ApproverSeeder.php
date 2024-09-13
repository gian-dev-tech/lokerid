<?php

namespace Database\Seeders;

use App\Models\Approver;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApproverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Approver::create(['name' => 'Approver A']);
        Approver::create(['name' => 'Approver B']);
        Approver::create(['name' => 'Approver C']);
    }
}
