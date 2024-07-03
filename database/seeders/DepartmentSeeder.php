<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Department::truncate();
        $data = [
            ['id' => 1, 'name' => 'Paint', 'payroll_type' => 'Percentage'],
            ['id' => 2, 'name' => 'Janitor', 'payroll_type' => 'Fixed'], 
            ['id' => 3, 'name' => 'Cleaning', 'payroll_type' => 'Fixed'],  
            ['id' => 4, 'name' => 'Flooring', 'payroll_type' => 'Fixed'],  
            ['id' => 5, 'name' => 'Punch-Out', 'payroll_type' => 'Percentage'], 
            ['id' => 6, 'name' => 'Refinish', 'payroll_type' => 'Fixed'],           
        ];
        Department::insert($data);
    }
}
