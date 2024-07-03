<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPaintSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_paint_specs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('paint_manufacturer_id');
            $table->unsignedBigInteger('paint_surface_id');
            $table->unsignedBigInteger('paint_color_id');
            $table->unsignedBigInteger('paint_finish_id');
            $table->unsignedBigInteger('paint_grade_id');
            $table->integer('coats');
            $table->unsignedBigInteger('paint_method_id');
            $table->string('paint_provider')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            //$table->foreign('property_id')->references('id')->on('client_properties');
            //$table->foreign('paint_manufacturer_id')->references('id')->on('paint_manufacturers');
            //$table->foreign('paint_surface_id')->references('id')->on('paint_surfaces');
            //$table->foreign('paint_color_id')->references('id')->on('paint_colors');
            //$table->foreign('paint_finish_id')->references('id')->on('paint_finishings');
            //$table->foreign('paint_grade_id')->references('id')->on('paint_grades');
            //$table->foreign('paint_method_id')->references('id')->on('paint_methods');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_paint_specs');
    }
}
