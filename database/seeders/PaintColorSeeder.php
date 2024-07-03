<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintColor;

class PaintColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintColor::truncate();
        $data = [
            ['name' => 'Red', 'hex_value' => '#f31212'], 
            ['name' => 'Blue', 'hex_value' => '#1f3fb2'], 
            ['name' => 'Green', 'hex_value' => '#13fb64'],            
        ];
        PaintColor::insert($data);
    }
}
