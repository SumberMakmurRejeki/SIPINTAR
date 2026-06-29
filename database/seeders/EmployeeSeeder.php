<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departmentIds = Department::pluck('id')->toArray();
        $positionIds = Position::pluck('id')->toArray();

        if (empty($departmentIds) || empty($positionIds)) {
            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $user = User::firstOrCreate(
                ['username' => "karyawan{$i}"],
                [
                    'name' => "Karyawan {$i}",
                    'email' => "karyawan{$i}@example.com",
                    'password' => bcrypt('password'),
                    'role' => 'employee',
                    'status' => 'active',
                ]
            );

            $user->employee()->firstOrCreate(
                ['user_id' => $user->id],
                [
                    'department_id' => $departmentIds[array_rand($departmentIds)],
                    'position_id' => $positionIds[array_rand($positionIds)],
                    'employee_code' => 'EMP-'.str_pad($i, 5, '0', STR_PAD_LEFT),
                    'name' => "Karyawan {$i}",
                    'status' => 'active',
                ]
            );
        }
    }
}
