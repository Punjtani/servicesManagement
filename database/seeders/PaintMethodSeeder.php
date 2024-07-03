<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintMethod;

class PaintMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintMethod::truncate();
        $data = [
            ['name' => 'Spray Paint'],   
            ['name' => 'Brush Paint'],            
        ];
        PaintMethod::insert($data);
    }
}
