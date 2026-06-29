<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['name' => 'Staff', 'description' => 'Staff level'],
            ['name' => 'Supervisor', 'description' => 'Supervisor level'],
            ['name' => 'Manager', 'description' => 'Manager level'],
            ['name' => 'Admin Training', 'description' => 'Training administrator'],
        ];

        foreach ($positions as $pos) {
            Position::firstOrCreate(
                ['name' => $pos['name']],
                $pos
            );
        }
    }
}
