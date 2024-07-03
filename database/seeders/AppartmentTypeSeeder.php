<?php

namespace Database\Seeders;

use App\Models\AppartmentType;
use Illuminate\Database\Seeder;

class AppartmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AppartmentType::truncate();
        $data = [
            ['type' => 'Studio', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => '1 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => '2 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => '3 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => '4 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => 'Townhouse 2 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => 'Townhouse 3 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
            ['type' => 'Townhouse 4 Bedroom', 'created_by' => '0', 'updated_by' => '0'],
        ];
        AppartmentType::insert($data);



    }
}
