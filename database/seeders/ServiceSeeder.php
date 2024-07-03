<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::truncate();
        $data = [
            ["id" => 1, "department_id" => 3, "category" => "CA Carpet Cleaning", "payroll_type" => "Hourly","sequence_order" => 5], 
            ["id" => 2, "department_id" => 4, "category" => "FL Flooring", "payroll_type" => "Hourly", "sequence_order" => 6], 
            ["id" => 3, "department_id" => 2, "category" => "JA Janitorial Cleaning", "payroll_type" => "Hourly", "sequence_order" => 3], 
            ["id" => 4, "department_id" => 1, "category" => "PT Paint", "payroll_type" => "Hourly", "sequence_order" => 1], 
            ["id" => 5, "department_id" => 5, "category" => "PU Punch-Out", "payroll_type" => "Hourly", "sequence_order" => 2], 
            ["id" => 6, "department_id" => 6, "category" => "RF Refinish", "payroll_type" => "Hourly", "sequence_order" => 4], 
           
        ];
        Service::insert($data);
    }
}
