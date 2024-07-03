<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyIdAndStaffIdToStaffRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('staff_role', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->after('isActive')->nullable();
            $table->unsignedBigInteger('property_id')->after('isActive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('staff_role', function (Blueprint $table) {
            //
        });
    }
}
