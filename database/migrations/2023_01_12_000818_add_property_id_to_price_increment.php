<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyIdToPriceIncrement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_increment', function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->nullable()->after('percentage');
            $table->dropColumn('client_id');
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
            $table->dropColumn('property_id');
            $table->unsignedBigInteger('client_id')->nullable()->after('percentage');
        });
    }
}
