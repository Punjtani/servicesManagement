<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAllTablesAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->unsignedBigInteger('role')->default(1)->change();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('crews', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('department_id')->references('id')->on('departments');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('service_sub_categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('parent_category_id')->references('id')->on('services');            
        });

        Schema::table('paint_manufacturers', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('paint_methods', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('paint_finishings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('paint_colors', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('paint_surfaces', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('paint_grades', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tax_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('client_properties', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('tax_type_id')->references('id')->on('tax_types');
        });

        Schema::table('apartment_types', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('client_suppliers', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('client_id')->references('id')->on('clients');
        });

        Schema::table('client_paint_specs', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('property_id')->references('id')->on('client_properties');
            $table->foreign('paint_manufacturer_id')->references('id')->on('paint_manufacturers');
            $table->foreign('paint_surface_id')->references('id')->on('paint_surfaces');
            $table->foreign('paint_color_id')->references('id')->on('paint_colors');
            $table->foreign('paint_finish_id')->references('id')->on('paint_finishings');
            $table->foreign('paint_grade_id')->references('id')->on('paint_grades');
            $table->foreign('paint_method_id')->references('id')->on('paint_methods');
        });

        Schema::table('service_ready_checklists', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('property_id')->references('id')->on('client_properties');
            $table->foreign('apartment_type_id')->references('id')->on('apartment_types');
        });

        Schema::table('job_service_ready_checklist', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('service_ready_checklist_id')->references('id')->on('service_ready_checklists');
        });

        Schema::table('requested_job_services', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('sub_service_id')->references('id')->on('service_sub_categories');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments');
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::table('employee_crews', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('crew_id')->references('id')->on('crews');
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('request_attacthments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('request_id')->references('id')->on('requests');
        });

        Schema::table('service_job_progresses', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('requested_job_service_id')->references('id')->on('requested_job_services');
        });

        Schema::table('service_job_progress_attatchments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('service_job_progress_id','sjp_id')->references('id')->on('service_job_progresses');
        });

        // Schema::table('management_extra', function (Blueprint $table) {
        //     $table->dropSoftDeletes();
        //     $table->foreign('employee_id')->references('id')->on('employees');
        // });

        Schema::table('client_bills', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('client_id')->references('id')->on('clients');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('employee_id')->references('id')->on('employees');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('job_id')->references('id')->on('jobs');
            $table->foreign('sub_service_id')->references('id')->on('service_sub_categories');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('job_id')->references('id')->on('jobs');
        });

        Schema::table('deposits', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });

        Schema::table('states', function (Blueprint $table) {
            $table->foreign('country_id')->references('id')->on('countries'); 
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('state_id')->references('id')->on('states'); 
        });

        Schema::table('service_subcategory_apartment_price', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('client_pricings', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('property_id')->references('id')->on('client_properties');
            $table->foreign('apartment_type_id')->references('id')->on('apartment_types');
            $table->foreign('service_type_id')->references('id')->on('services');
            $table->foreign('sub_service_id')->references('id')->on('service_sub_categories');
        });

        Schema::table('job_attatchments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->foreign('job_id')->references('id')->on('jobs');
        });
        Schema::enableForeignKeyConstraints();
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
