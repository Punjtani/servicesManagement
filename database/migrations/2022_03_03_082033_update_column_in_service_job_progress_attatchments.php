<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnInServiceJobProgressAttatchments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE service_job_progress_attatchments DROP FOREIGN KEY sjp_id");
        Schema::table('service_job_progress_attatchments', function (Blueprint $table) {   
            $table->dropColumn('service_job_progress_id');
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
            $table->unsignedBigInteger('service_job_progress_id');
        });
    }
}
