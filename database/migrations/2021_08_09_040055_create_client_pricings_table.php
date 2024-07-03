<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_pricings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('apartment_type_id');
            $table->unsignedBigInteger('service_type_id');
            $table->unsignedBigInteger('sub_service_id');
            $table->float('price');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('property_id')->references('id')->on('client_properties');
            //$table->foreign('apartment_type_id')->references('id')->on('apartment_types');
            //$table->foreign('service_type_id')->references('id')->on('services');
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
        Schema::dropIfExists('client_pricings');
    }
}
