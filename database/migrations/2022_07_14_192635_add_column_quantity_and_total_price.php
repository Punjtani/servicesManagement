<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnQuantityAndTotalPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('quantity')->after('base_price')->default(1);
            $table->float('total_price')->after('quantity');
        });
        \DB::statement('UPDATE requested_job_sub_services SET total_price = base_price');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_job_sub_services', function (Blueprint $table) {
            //
            $table->dropColumn('quantity');
            $table->dropColumn('total_price');
        });
    }
}
