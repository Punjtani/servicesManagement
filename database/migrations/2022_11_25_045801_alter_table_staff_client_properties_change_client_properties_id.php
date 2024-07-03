<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableStaffClientPropertiesChangeClientPropertiesId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_client_properties', function (Blueprint $table) {
            $table->string('client_properties_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_client_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('client_properties_id')->change();
        });
    }
}
