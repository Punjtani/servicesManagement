<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsQuoteInClientPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_properties', function (Blueprint $table) {
            //
            $table->boolean('is_quote')->default(0)->after('is_separate_billing');
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
            //
            $table->dropColumn('is_quote');
        });
    }
}
