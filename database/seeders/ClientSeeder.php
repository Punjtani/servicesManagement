<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::truncate();
        $data = [
            ['user_id' => 2, 'apartment_name' => 'Test Appart', 'company' => 'Quynn Bernard', 'street_address' => 'Culpa tempora modi f', 'notes' => 'Notes', 'city' => '' , 'state' => '', 
             'country' => '' , 'zipcode' => 69945, 'phone' => 1375719203, 'web' => '', 'manager' => 1, 'contact_title' => 'MR', 'contact_name' => 'Ashely Branch', 'date_of_birth' => '2021-09-01'   
            ],            
        ];
        Client::insert($data);
    }
}
