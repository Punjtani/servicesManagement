<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToPriceIncrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_increment', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->after('percentage');
            $table->boolean('is_already_incremented')->default('0')->after('service_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('price_increment', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropColumn('is_already_incremented');
        });
    }
}
