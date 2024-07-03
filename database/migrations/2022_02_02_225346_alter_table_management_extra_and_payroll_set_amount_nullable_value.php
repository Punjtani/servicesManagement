<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableManagementExtraAndPayrollSetAmountNullableValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('management_extra_and_payroll', function (Blueprint $table) {
            //
            $table->float('amount')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('management_extra_and_payroll', function (Blueprint $table) {
            //
            $table->float('amount')->change();
        });
    }
}
