<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdToClientPropertyPricingNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_property_pricing_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('service_id')->after('notes_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_property_pricing_notes', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });
    }
}
