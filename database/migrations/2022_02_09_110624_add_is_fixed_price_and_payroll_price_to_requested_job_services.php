<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsFixedPriceAndPayrollPriceToRequestedJobServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_job_services', function (Blueprint $table) {
            //
            $table->boolean('is_fixed_price')->nullable()->default('0')->after('end_date');
            $table->float('payroll_price')->nullable()->after('is_fixed_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_job_services', function (Blueprint $table) {
            //
            $table->dropColumn('is_fixed_price');
            $table->dropColumn('payroll_price');
        });
    }
}
