<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRjsIdToJobAttatchments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_attatchments', function (Blueprint $table) {
            $table->unsignedBigInteger('rjs_id')->nullable()->after('job_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_attatchments', function (Blueprint $table) {
            $table->dropColumn('rjs_id');
        });
    }
}
