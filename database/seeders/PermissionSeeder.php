<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();
        $data = [
            // custom 
            ['name' => 'All permissions', 'slug' => 'can-view-appartment-types'],
            ['name' => 'viewDashboardStats', 'slug' => 'can-view-dashboard-stats'],

            ['name' => 'viewAppartmentTypes', 'slug' => 'can-view-appartment-types'],
            ['name' => 'addAppartmentTypes', 'slug' => 'can-add-appartment-types'],
            ['name' => 'editAppartmentTypes', 'slug' => 'can-edit-appartment-types'],
            ['name' => 'deleteAppartmentTypes', 'slug' => 'can-delete-appartment-types'],

            ['name' => 'viewServiceTypes', 'slug' => 'can-view-service-types'],
            ['name' => 'addServiceTypes', 'slug' => 'can-add-service-types'],
            ['name' => 'editServiceTypes', 'slug' => 'can-edit-service-types'],
            ['name' => 'deleteServiceTypes', 'slug' => 'can-delete-service-types'],

            ['name' => 'viewPaintColors', 'slug' => 'can-view-paint-colors'],
            ['name' => 'addPaintColors', 'slug' => 'can-add-paint-colors'],
            ['name' => 'editPaintColors', 'slug' => 'can-edit-paint-colors'],
            ['name' => 'deletePaintColors', 'slug' => 'can-delete-paint-colors'],

            ['name' => 'viewPaintGrades', 'slug' => 'can-view-paint-grades'],
            ['name' => 'addPaintGrades', 'slug' => 'can-add-paint-grades'],
            ['name' => 'editPaintGrades', 'slug' => 'can-edit-paint-grades'],
            ['name' => 'deletePaintGrades', 'slug' => 'can-delete-paint-grades'],

            ['name' => 'viewPaintFinishing', 'slug' => 'can-view-paint-finishing'],
            ['name' => 'addPaintFinishing', 'slug' => 'can-add-paint-finishing'],
            ['name' => 'editPaintFinishing', 'slug' => 'can-edit-paint-finishing'],
            ['name' => 'deletePaintFinishing', 'slug' => 'can-delete-paint-finishing'],

            ['name' => 'viewPaintManufacturer', 'slug' => 'can-view-paint-manufacturer'],
            ['name' => 'addPaintManufacturer', 'slug' => 'can-add-paint-manufacturer'],
            ['name' => 'editPaintManufacturer', 'slug' => 'can-edit-paint-manufacturer'],
            ['name' => 'deletePaintManufacturer', 'slug' => 'can-delete-paint-manufacturer'],

            ['name' => 'viewPaintMethod', 'slug' => 'can-view-paint-method'],
            ['name' => 'addPaintMethod', 'slug' => 'can-add-paint-method'],
            ['name' => 'editPaintMethod', 'slug' => 'can-edit-paint-method'],
            ['name' => 'deletePaintMethod', 'slug' => 'can-delete-paint-method'],

            ['name' => 'viewPaintSurface', 'slug' => 'can-view-paint-surface'],
            ['name' => 'addPaintSurface', 'slug' => 'can-add-paint-surface'],
            ['name' => 'editPaintSurface', 'slug' => 'can-edit-paint-surface'],
            ['name' => 'deletePaintSurface', 'slug' => 'can-delete-paint-surface'],

            ['name' => 'viewClient', 'slug' => 'can-view-client'],
            ['name' => 'addClient', 'slug' => 'can-add-client'],
            ['name' => 'editClient', 'slug' => 'can-edit-client'],
            ['name' => 'deleteClient', 'slug' => 'can-delete-client'],

            ['name' => 'viewProperty', 'slug' => 'can-view-property'],
            ['name' => 'addProperty', 'slug' => 'can-add-property'],
            ['name' => 'editProperty', 'slug' => 'can-edit-property'],
            ['name' => 'deleteProperty', 'slug' => 'can-delete-property'],

            ['name' => 'viewClientProperties', 'slug' => 'can-view-client-properties'],
            ['name' => 'addviewClientProperties', 'slug' => 'can-add-client-properties'],
            ['name' => 'editviewClientProperties', 'slug' => 'can-edit-client-properties'],
            ['name' => 'deleteviewClientProperties', 'slug' => 'can-delete-client-properties'],

            ['name' => 'viewTaxType', 'slug' => 'can-view-tax-type'],
            ['name' => 'addTaxType', 'slug' => 'can-add-tax-type'],
            ['name' => 'editTaxType', 'slug' => 'can-edit-tax-type'],
            ['name' => 'deleteTaxType', 'slug' => 'can-delete-tax-type'],

            ['name' => 'viewDepartment', 'slug' => 'can-view-department'],
            ['name' => 'addDepartment', 'slug' => 'can-add-department'],
            ['name' => 'editDepartment', 'slug' => 'can-edit-department'],
            ['name' => 'deleteDepartment', 'slug' => 'can-delete-department'],

            ['name' => 'viewCrew', 'slug' => 'can-view-crew'],
            ['name' => 'addCrew', 'slug' => 'can-add-crew'],
            ['name' => 'editCrew', 'slug' => 'can-edit-crew'],
            ['name' => 'deleteCrew', 'slug' => 'can-delete-crew'],

            ['name' => 'vieweEmploye', 'slug' => 'can-view-employe'],
            ['name' => 'addEmploye', 'slug' => 'can-add-employe'],
            ['name' => 'editEmploye', 'slug' => 'can-edit-employe'],
            ['name' => 'deleteEmploye', 'slug' => 'can-delete-employe'],

            ['name' => 'viewSubCategory', 'slug' => 'can-view-sub-category'],
            ['name' => 'addSubCategory', 'slug' => 'can-add-sub-category'],
            ['name' => 'editSubCategory', 'slug' => 'can-edit-sub-category'],
            ['name' => 'deleteSubCategory', 'slug' => 'can-delete-sub-category'],

            ['name' => 'viewCrewEmployees', 'slug' => 'can-view-crew-employees'],
            ['name' => 'addCrewEmployees', 'slug' => 'can-add-crew-employees'],
            ['name' => 'editCrewEmployees', 'slug' => 'can-edit-crew-employees'],
            ['name' => 'deleteCrewEmployees', 'slug' => 'can-delete-crew-employees'],

            ['name' => 'viewClientPropertyPricing', 'slug' => 'can-view-client-property-pricing'],
            ['name' => 'addClientPropertyPricing', 'slug' => 'can-add-client-property-pricing'],
            ['name' => 'editClientPropertyPricing', 'slug' => 'can-edit-client-property-pricing'],
            ['name' => 'deleteClientPropertyPricing', 'slug' => 'can-delete-client-property-pricing'],

            ['name' => 'viewJob', 'slug' => 'can-view-job'],
            ['name' => 'addJob', 'slug' => 'can-add-job'],
            ['name' => 'editJob', 'slug' => 'can-edit-job'],
            ['name' => 'deleteJob', 'slug' => 'can-delete-job'],
            ['name' => 'viewJobInitialData', 'slug' => 'can-view-job-initial-data'],
            ['name' => 'viewAssignJob', 'slug' => 'can-view-job-assign'],
            ['name' => 'assignJob', 'slug' => 'can-assign-job'],
             ['name' => 'jobScheduleDates', 'slug' => 'can-schedule-job-dates'],

            ['name' => 'viewCrew', 'slug' => 'can-view-property-services'],
            
            ['name' => 'addFileUpload', 'slug' => 'can-add-file-upload'],
            
            ['name' => 'viewRolePermissions', 'slug' => 'can-view-role-permissions'],
            ['name' => 'addRolePermissions', 'slug' => 'can-add-role-permissions'],
            ['name' => 'deleteRolePermissions', 'slug' => 'can-delete-role-permissions'],
            
        ];
        Permission::insert($data);
    }
}
