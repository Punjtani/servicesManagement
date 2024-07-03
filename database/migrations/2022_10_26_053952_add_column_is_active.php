<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_sub_categories', function (Blueprint $table) {
            //
            $table->boolean('isActive')->default('1')->after('is_show_to_client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_sub_categories', function (Blueprint $table) {
            //
            $table->dropColumn('isActive');
        });
    }
}
