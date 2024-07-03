<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnToManagementExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_extra', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('notes');
            $table->dropColumn('bonus_date');
            
            $table->unsignedBigInteger('job_id')->after('amount')->nullable();
            $table->boolean('is_paid_payroll')->after('job_id')->nullable();
            $table->float('paid_payroll_price')->after('is_paid_payroll')->nullable();
            
            
        });
        Schema::rename('management_extra', 'management_extra_and_payroll');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('management_extra_and_payroll', 'management_extra');
        Schema::table('management_extra', function (Blueprint $table) {
            
            $table->date('bonus_date');
            $table->longText('notes');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->dropColumn('job_id');
            $table->dropColumn('paid_payroll_price');
            $table->dropColumn('is_paid_payroll');
        });
        

    }
}
