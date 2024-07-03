<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTaxRateToClientProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('tax_rate')->nullable()->after('tax_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            $table->dropColumn('tax_rate');
        });
    }
}
