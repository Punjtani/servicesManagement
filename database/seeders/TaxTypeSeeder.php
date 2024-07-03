<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxType;

class TaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaxType::truncate();
        $data = [
            ["name" => "Maryland", "rate" => "6"],
            ["name" => "DC", "rate" => "6"],
            ["name" => "No Tax", "rate" => "0"],
        ];
        TaxType::insert($data);
    }
}
