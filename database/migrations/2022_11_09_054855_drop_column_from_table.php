<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnFromTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('price_notes', function (Blueprint $table) {
            //
            $table->dropColumn('apartment_type_id');
            $table->dropColumn('price');
            
        });
        Schema::table('client_property_pricing_notes', function (Blueprint $table) {
            //
            $table->dropColumn('apartment_type_id');
            $table->dropColumn('price');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
