<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceTagToServiceSubcategoryApartmentPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_subcategory_apartment_price', function (Blueprint $table) {
            //
            $table->string('price_tag')->nullable()->after('base_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_subcategory_apartment_price', function (Blueprint $table) {
            //
            $table->dropColumn("price_tag");
        });
    }
}
