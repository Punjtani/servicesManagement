<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Crew;

class CrewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Crew::truncate();
        $data = [
            ['name' => 'Paint Crew 1', 'department_id' => 1],            
            ['name' => 'Paint Crew 2', 'department_id' => 1],            
            ['name' => 'Janitor 1', 'department_id' => 2],            
        ];
        Crew::insert($data);
    }
}
