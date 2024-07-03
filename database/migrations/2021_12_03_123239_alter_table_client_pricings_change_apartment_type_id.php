<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientPricingsChangeApartmentTypeId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_pricings', function (Blueprint $table) {
            $table->unsignedBigInteger('apartment_type_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_pricings', function (Blueprint $table) {
            $table->unsignedBigInteger('apartment_type_id')->nullable(false)->change();
        });
    }
}
