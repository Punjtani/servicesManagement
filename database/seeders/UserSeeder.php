<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $data = [
            ['id'=> 1, 'first_name' => 'admin', 'middle_name' => '',  'last_name' => 'user', 'email' => 'admin@admin.com', 'role' => '1', 'password' => bcrypt('admin'), 'is_active' => '1'], 
            ['id'=> 2, 'first_name' => 'client', 'middle_name' => '',  'last_name' => 'user', 'email' => 'client@gmail.com', 'role' => '2', 'password' => bcrypt('client'), 'is_active' => '1'], 
            ['id'=> 4, 'first_name' => 'super-admin', 'middle_name' => '',  'last_name' => 'user', 'email' => 'superadmin@gmail.com', 'role' => '4', 'password' => bcrypt('superadmin'), 'is_active' => '1'], 
            ['id'=> 3, 'first_name' => 'employee', 'middle_name' => 'paint', 'last_name' => 'user', 'email' => 'employee@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],
            ['id'=> 5, 'first_name' => 'employee', 'middle_name' => 'janitor', 'last_name' => 'user', 'email' => 'employee.jan@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],
            ['id'=> 6, 'first_name' => 'employee', 'middle_name' => 'cleaning', 'last_name' => 'user', 'email' => 'employee.clean@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],           
            ['id'=> 7, 'first_name' => 'employee', 'middle_name' => 'flooring', 'last_name' => 'user', 'email' => 'employee.floor@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],
            ['id'=> 8, 'first_name' => 'employee', 'middle_name' => 'punch-out', 'last_name' => 'user', 'email' => 'employee.punch@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],           
            ['id'=> 9, 'first_name' => 'employee', 'middle_name' => 'refinish', 'last_name' => 'user', 'email' => 'employee.refinish@gmail.com', 'role' => '3', 'password' => bcrypt('employee'), 'is_active' => '1'],           

        ];
        User::insert($data);
    }
}
