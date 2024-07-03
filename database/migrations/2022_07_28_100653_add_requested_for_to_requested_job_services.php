<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestedForToRequestedJobServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_job_services', function (Blueprint $table) {
            //
            $table->date('requested_for')->nullable()->after('requested_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_job_services', function (Blueprint $table) {
            //
            $table->dropColumn('requested_for');
        });
    }
}
