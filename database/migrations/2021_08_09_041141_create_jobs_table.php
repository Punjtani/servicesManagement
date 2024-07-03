<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('apartment_type_id');
            $table->string('apartment_number')->nullable();;
            $table->enum('apartment_status',['vacant','occupied']);
            $table->string('po_number');
            $table->enum('job_status',['scheduled','not scheduled']);
            $table->boolean('is_assigned');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('property_id')->references('id')->on('client_properties');
            //$table->foreign('apartment_type_id')->references('id')->on('apartment_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
