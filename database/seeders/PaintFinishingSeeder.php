<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintFinishing;

class PaintFinishingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintFinishing::truncate();
        $data = [
            ['name' => 'Gloss'],
            ['name' => 'Matte'],
            ['name' => 'Textured'],            
        ];
        PaintFinishing::insert($data);
    }
}
