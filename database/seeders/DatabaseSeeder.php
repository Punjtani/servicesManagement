<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $this->call([
            UserSeeder::class,
           AppartmentTypeSeeder::class,
           PermissionSeeder::class,
           CountriesSeeder::class,
           StatesSeeder::class,
           CitiesSeeder::class,
           RoleSeeder::class,
           RolePermissionSeeder::class,
           DepartmentSeeder::class,
           ClientSeeder::class,
           EmployeeSeeder::class,
           ServiceSeeder::class,
           ServiceSubCategorySeeder::class,
           ServiceSubCategoryAppartmentPriceSeeder::class,
           ServiceReadyChecklistSeeder::class,
           TaxTypeSeeder::class
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    }
}
