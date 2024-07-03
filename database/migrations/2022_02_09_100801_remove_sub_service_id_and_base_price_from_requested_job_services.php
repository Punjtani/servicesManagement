<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveSubServiceIdAndBasePriceFromRequestedJobServices extends Migration
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
            $table->dropForeign('requested_job_services_sub_service_id_foreign');
            $table->dropColumn('sub_service_id');
            $table->dropColumn('base_price');
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
            $table->unsignedBigInteger('sub_service_id');
            $table->float('base_price');
        });
    }
}
