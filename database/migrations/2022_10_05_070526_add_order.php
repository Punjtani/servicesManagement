<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrder extends Migration
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
            $table->unsignedBigInteger('order')->default(0)->after('is_invoice');
        });
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
            $table->dropColumn('order');
        });
    }
}
