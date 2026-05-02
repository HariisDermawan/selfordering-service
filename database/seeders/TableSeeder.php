<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Enums\TableStatus;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            ['table_number' => 'T1', 'capacity' => 2, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T2', 'capacity' => 2, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T3', 'capacity' => 4, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T4', 'capacity' => 4, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T5', 'capacity' => 6, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T6', 'capacity' => 6, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T7', 'capacity' => 8, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T8', 'capacity' => 8, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T9', 'capacity' => 10, 'status' => TableStatus::AVAILABLE],
            ['table_number' => 'T10', 'capacity' => 10, 'status' => TableStatus::AVAILABLE],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }
    }
}