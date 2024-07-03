<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestedJobServiceIdAndBasePriceToRequestedJobSubServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('requested_job_service_id')->after('sub_service_id');
            $table->float('base_price')->after('requested_job_service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            //
             $table->dropColumn('base_price');
             $table->dropColumn('requested_job_service_id');
        });
    }
}
