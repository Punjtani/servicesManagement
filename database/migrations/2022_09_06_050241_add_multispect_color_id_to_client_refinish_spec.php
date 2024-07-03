<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMultispectColorIdToClientRefinishSpec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_refinish_spec', function (Blueprint $table) {
            $table->unsignedBigInteger('multispect_color_id')->after('paint_color_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_refinish_spec', function (Blueprint $table) {
            $table->dropColumn('multispect_color_id');
        });
    }
}
