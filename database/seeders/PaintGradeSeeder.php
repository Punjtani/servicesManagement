<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintGrade;

class PaintGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintGrade::truncate();
        $data = [
            ['name' => 'A+'],
            ['name' => 'B+'],            
            ['name' => 'B-'],
        ];
        PaintGrade::insert($data);
    }
}
