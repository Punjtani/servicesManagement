<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedJobServiceIdInTableServiceJobProgressAttatchments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_job_progress_attatchments', function (Blueprint $table) {
            $table->unsignedBigInteger('requested_job_service_id')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_job_progress_attatchments', function (Blueprint $table) {
            $table->dropColumn('requested_job_service_id');
        });
    }
}
