<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaintSurface;

class PaintSurfaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaintSurface::truncate();
        $data = [
            ['name' => 'Wall'],
            ['name' => 'Ceiling'],            
            ['name' => 'Door'],            
            ['name' => 'Counter top'],            
        ];
        PaintSurface::insert($data);
    }
}
