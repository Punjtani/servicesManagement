<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestedJobServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_job_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('sub_service_id');
            $table->float('base_price');
            $table->integer('assigned_to_id')->nullable();
            $table->enum('assigned_to_type',['crew', 'individual'])->nullable();
            $table->date('requested_date');
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable()->default('09:00:00');
            $table->boolean('anytime')->nullable()->default('0');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('billed_date')->nullable();
            $table->boolean('is_paid')->nullable()->default('0');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('job_id')->references('id')->on('jobs');
            //$table->foreign('service_id')->references('id')->on('services');
            //$table->foreign('sub_service_id')->references('id')->on('service_sub_categories');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requested_job_services');
    }
}
