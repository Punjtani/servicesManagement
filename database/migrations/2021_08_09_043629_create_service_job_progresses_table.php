<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceJobProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_job_progresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requested_job_service_id');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable()->default(null);
            $table->longText('notes')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('requested_job_service_id')->references('id')->on('requested_job_services');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_job_progresses');
    }
}
