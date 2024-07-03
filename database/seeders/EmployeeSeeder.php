<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employe;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employe::truncate();
        $data = [
            [ 
              'id' => 1,'user_id' => 3, 'department_id' => 1,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'percentage', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            [ 
              'id' => 2,'user_id' => 5, 'department_id' => 2,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'fixed', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            [ 
              'id' => 3,'user_id' => 6, 'department_id' => 3,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'fixed', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            [ 
              'id' => 4,'user_id' => 7, 'department_id' => 4,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'fixed', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            [ 
              'id' => 5,'user_id' => 8, 'department_id' => 5,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'percentage', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            [ 
              'id' => 6,'user_id' => 9, 'department_id' => 6,'contract_start_date' => '2021-10-06', 'contract_end_date' => '2021-10-22',
              'salary_type'=> 'fixed', 'salary_value' => 40, 't_shirt_size' => 'Large', 'address'=> 'NXS: ressys-037 PO Box 7655 Merrifield, VA 22116-7644', 'notes'=> ''  
            ],
            
        ];
        Employe::insert($data);
    }
}
