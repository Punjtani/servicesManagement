<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();
        $data = [
            // custom 
            ['id' => 1,'code' => 'US','name' => "United States",'phonecode' => 1],
        ];
        Country::insert($data);
    }
}
