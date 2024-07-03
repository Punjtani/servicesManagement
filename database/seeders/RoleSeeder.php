<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::truncate();
        $data = [
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'client'],
            ['id' => 3, 'name' => 'employee'],            
            ['id' => 4, 'name' => 'super-admin'],
        ];
        Role::insert($data);
    }
}
