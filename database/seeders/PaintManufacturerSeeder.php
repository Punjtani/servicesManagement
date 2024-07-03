<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintManufacturer;

class PaintManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintManufacturer::truncate();
        $data = [
            ['name' => 'Master'],
            ['name' => 'Dulux'],            
        ];
        PaintManufacturer::insert($data);
    }
}
