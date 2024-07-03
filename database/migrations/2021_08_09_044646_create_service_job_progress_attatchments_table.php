<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceJobProgressAttatchmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_job_progress_attatchments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_job_progress_id');
            $table->string('file_name');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            //$table->foreign('service_job_progress_id')->references('id')->on('service_job_progresses');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_job_progress_attatchments');
    }
}
