<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveJobIdFromRequestedJobSubServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            $table->dropForeign('requested_job_sub_services_job_id_foreign');
            $table->dropColumn('job_id');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
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
            $table->unsignedBigInteger('job_id');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }
}
