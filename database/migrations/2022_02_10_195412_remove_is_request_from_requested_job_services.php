<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveIsRequestFromRequestedJobServices extends Migration
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
            $table->dropColumn('is_request');
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
        Schema::table('requested_job_services', function (Blueprint $table) {
            //
            $table->boolean('is_request')->nullable()->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
        });
    }
}
