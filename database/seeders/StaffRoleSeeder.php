<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StaffRole;

class StaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffRole::truncate();
        $data = [
            ['name' => 'Vice President', 'is_property_level'=> 0],
            ['name' => 'President', 'is_property_level'=> 0], 
            ['name' => 'Regional Manager', 'is_property_level'=> 1],
            ['name' => 'Property Manager', 'is_property_level'=> 1],
            ['name' => 'Turnover Coordinator', 'is_property_level'=> 1], 
            ['name' => 'Maintenance Supervisor', 'is_property_level'=> 1], 
                       
        ];
        StaffRole::insert($data);
    }
}
