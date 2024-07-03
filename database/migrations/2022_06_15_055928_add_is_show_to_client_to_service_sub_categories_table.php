<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsShowToClientToServiceSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_sub_categories', function (Blueprint $table) {
            $table->boolean('is_show_to_client')->default(1)->after('is_independent');
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
            $table->dropColumn('is_show_to_client');
        });
    }
}
