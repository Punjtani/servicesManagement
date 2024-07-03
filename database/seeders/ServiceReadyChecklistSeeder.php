<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceReadyChecklist;

class ServiceReadyChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ServiceReadyChecklist::truncate();
        $data = [
            ['name' => 'Have keys. Contractor can gain easy access.'],
            ['name' => 'Repairs prior to turnover completed'],
            ['name' => 'Residents notified (if occupied)'],
            ['name' => 'Power in the apartment'],
            ['name' => 'Water in the apartment'],
            ['name' => 'No hazardous conditions present'],
            ['name' => 'Stove moved (If services require moving it)'],
        ];
        ServiceReadyChecklist::insert($data);
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
