<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteOnCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {

        // Schema::table('appartment_type_client_properties', function (Blueprint $table) {
        //     $table->dropForeign(['appartment_type_id']);
        //     $table->dropForeign(['client_properties_id']);
        //     $table->foreign('appartment_type_id')->references('id')->on('apartment_types')->onDelete('cascade');
        //     $table->foreign('client_properties_id')->references('id')->on('client_properties')->onDelete('cascade');
        // });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('client_paint_specs', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['paint_manufacturer_id']);
            $table->dropForeign(['paint_surface_id']);
            $table->dropForeign(['paint_color_id']);
            $table->dropForeign(['paint_finish_id']);
            $table->dropForeign(['paint_grade_id']);
            $table->dropForeign(['paint_method_id']);

            $table->foreign('property_id')->references('id')->on('client_properties')->onDelete('cascade');
            $table->foreign('paint_manufacturer_id')->references('id')->on('paint_manufacturers')->onDelete('cascade');
            $table->foreign('paint_surface_id')->references('id')->on('paint_surfaces')->onDelete('cascade');
            $table->foreign('paint_color_id')->references('id')->on('paint_colors')->onDelete('cascade');
            $table->foreign('paint_finish_id')->references('id')->on('paint_finishings')->onDelete('cascade');
            $table->foreign('paint_grade_id')->references('id')->on('paint_grades')->onDelete('cascade');
            $table->foreign('paint_method_id')->references('id')->on('paint_methods')->onDelete('cascade');
        });

        Schema::table('client_pricings', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['apartment_type_id']);
            $table->dropForeign(['service_type_id']);
            $table->dropForeign(['sub_service_id']);
            $table->foreign('property_id')->references('id')->on('client_properties')->onDelete('cascade');
            $table->foreign('apartment_type_id')->references('id')->on('apartment_types')->onDelete('cascade');
            $table->foreign('service_type_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('sub_service_id')->references('id')->on('service_sub_categories')->onDelete('cascade');
        });

        Schema::table('client_properties', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['tax_type_id']);
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('tax_type_id')->references('id')->on('tax_types')->onDelete('cascade');
        });

        // Schema::table('client_refinish_spec', function (Blueprint $table) {
        //     $table->dropForeign(['property_id']);
        //     $table->foreign('property_id')->references('id')->on('client_properties')->onDelete('cascade');
        // });

        Schema::table('crews', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        // Schema::table('department_payroll_price', function (Blueprint $table) {
        //     $table->dropForeign(['department_id']);
        //     $table->dropForeign(['sub_service_id']);
        //     $table->dropForeign(['appartment_type_id']);
        //     $table->unsignedBigInteger('appartment_type_id')->change();
        //     $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        //     $table->foreign('sub_service_id')->references('id')->on('service_sub_categories')->onDelete('cascade');
        //     $table->foreign('appartment_type_id')->references('id')->on('apartment_types')->onDelete('cascade');
        // });

        Schema::table('deposits', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['department_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });

        Schema::table('employee_crews', function (Blueprint $table) {
            $table->dropForeign(['crew_id']);
            $table->dropForeign(['employee_id']);
            $table->foreign('crew_id')->references('id')->on('crews')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropForeign(['apartment_type_id']);
            $table->foreign('property_id')->references('id')->on('client_properties')->onDelete('cascade');
            $table->foreign('apartment_type_id')->references('id')->on('apartment_types')->onDelete('cascade');
        });

        Schema::table('job_attatchments', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        });

        Schema::table('job_service_ready_checklist', function (Blueprint $table) {
            $table->dropForeign(['job_id']);
            $table->dropForeign(['service_ready_checklist_id']);
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            $table->foreign('service_ready_checklist_id')->references('id')->on('service_ready_checklists')->onDelete('cascade');
        });

        // Schema::table('management_extra_and_payroll', function (Blueprint $table) {
        //     $table->dropForeign(['job_id']);
        //     $table->dropForeign(['employee_id']);
        //     $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
        //     $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        // });

        // Schema::table('quote_attatchments', function (Blueprint $table) {
        //     $table->dropForeign(['client_properties_id']);            
        //     $table->foreign('client_properties_id')->references('id')->on('client_properties')->onDelete('cascade');

        // });

    //     Schema::table('requested_job_services', function (Blueprint $table) {
    //         $table->dropForeign(['job_id']);
    //         $table->dropForeign(['service_id']);
    //         $table->dropForeign(['assigned_to_id']);
    //         $table->unsignedBigInteger('assigned_to_id')->change();
    //         $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
    //         $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
    //         $table->foreign('assigned_to_id')->references('id')->on('employees')->onDelete('cascade');
    //     });

    //      Schema::table('requested_job_sub_services', function (Blueprint $table) {

    //         $table->dropForeign(['requested_job_service_id']);
    //         $table->dropForeign(['sub_service_id']);
    //         $table->foreign('requested_job_service_id')->references('id')->on('requested_job_services')->onDelete('cascade');
    //         $table->foreign('sub_service_id')->references('id')->on('service_sub_categories')->onDelete('cascade');
    //     });

    //      Schema::table('role_permissions', function (Blueprint $table) {
    //         $table->dropForeign(['role_id']);
    //         $table->dropForeign(['permission_id']);
    //         $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    //         $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
    //     });

    //     Schema::table('services', function (Blueprint $table) {
    //         $table->dropForeign(['department_id']);
    //         $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
    //     });

    //     Schema::table('service_job_progresses', function (Blueprint $table) {
    //         $table->dropForeign(['requested_job_service_id']);
    //         $table->foreign('requested_job_service_id')->references('id')->on('requested_job_services')->onDelete('cascade');
    //     });
    //    Schema::table('service_job_progress_attatchments', function (Blueprint $table) {
    //         $table->dropForeign('sjp_id');
    //         $table->foreign('requested_job_service_id','sjp_id')->references('id')->on('requested_job_services')->onDelete('cascade');
    //     });

    //     Schema::table('service_job_progress_after_attatchments', function (Blueprint $table) {
    //         $table->dropForeign('sjap_id');
    //         $table->foreign('requested_job_service_id','sjap_id')->references('id')->on('requested_job_services')->onDelete('cascade');
    //     });

    //     Schema::table('service_subcategory_apartment_price', function (Blueprint $table) {
    //         $table->dropForeign(['sub_service_id']);
    //         $table->dropForeign(['appartment_type_id']);
    //         $table->unsignedBigInteger('appartment_type_id')->change();
    //         $table->foreign('sub_service_id')->references('id')->on('service_sub_categories')->onDelete('cascade');
    //         $table->foreign('appartment_type_id')->references('id')->on('apartment_types')->onDelete('cascade');
    //     });

    //     Schema::table('service_sub_categories', function (Blueprint $table) {
    //         $table->dropForeign(['parent_category_id']);
    //         $table->foreign('parent_category_id')->references('id')->on('services')->onDelete('cascade');  
    //     });

    //      Schema::table('states', function (Blueprint $table) {
    //         $table->dropForeign(['country_id']);
    //         $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
    //     });

    //    Schema::table('users', function (Blueprint $table) {
    //         $table->dropForeign(['parent_id']);
    //         $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
    //     });

    //     Schema::table('user_roles', function (Blueprint $table) {
    //         $table->dropForeign(['role_id']);
    //         $table->dropForeign(['user_id']);
    //         $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    //         $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    //     });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
